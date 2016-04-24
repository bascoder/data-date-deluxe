<?php

/**
 * @property CI_DB_query_builder|CI_DB_pdo_driver db
 * @property CI_Image_lib image_lib
 * @property CI_Input input
 */
class Foto extends CI_Model
{


    /**
     * Foto constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->library('image_lib');
    }

    public function insert_foto($url, $profielId)
    {

    }

    public function query_by_id($fid)
    {
        if (!is_numeric($fid)) {
            throw new InvalidArgumentException('Fid moet nummer zijn');
        }
        $result = $this->db->get_where('foto', 'fid = ' . $fid)->first_row();
        if (isset($result)) {
            return $result;
        }
        return NULL;
    }

    public function query_by_profiel($profiel)
    {
        if (!isset($profiel)) {
            throw new InvalidArgumentException('Fid moet nummer zijn');
        }
        $pid = $profiel->pid;

        $result = $this->db->select('Foto.*')
            ->from('Foto')
            ->join('Profiel', 'Profiel.profiel_foto_id=Foto.fid')
            ->where('pid', $pid)
            ->get()
            ->first_row();
        if (isset($result)) {
            return $result;
        } else {
            $this->set_to_placeholder($profiel);
        }
        return NULL;
    }

    public function set_to_placeholder($profiel)
    {
        if (isset($profiel) && isset($profiel->geslacht_id) && isset($profiel->pid)) {
            $this->db->update('Profiel', array(
                'profiel_foto_id' => $profiel->geslacht_id
            ), 'pid = ' . $profiel->pid);
            return $this->query_by_id($profiel->geslacht_id);
        } else {
            throw new InvalidArgumentException('Je moet ingelogd zijn.');
        }
    }

    public function delete_profiel_foto($profiel)
    {
        if (isset($profiel) && isset($profiel->profiel_foto_id) && isset($profiel->geslacht_id)) {
            $profiel_foto_id = $profiel->profiel_foto_id;
            $geslacht = $profiel->geslacht_id;
            if ($profiel_foto_id == $geslacht) {
                throw new InvalidArgumentException('Je mag je placeholder foto niet verwijderen');
            }

            log_message('info', 'Foto verwijderen: ' . $this->delete_from_file_path($profiel_foto_id));
            $this->db->delete('Foto', 'fid = ' . $profiel_foto_id);
        } else {
            throw new InvalidArgumentException('Je moet al een profiel foto hebben.');
        }
    }

    public function insert_profiel_foto($file, $profiel_id)
    {
        $current_path = $file['full_path'];
        $this->resize_foto($current_path);
        $new_file = $this->generate_file_name($file);
        $new_path = 'assets/img/profiel_fotos/' . $new_file;

        if (rename($current_path, $new_path)) {

            // also create thumbnail
            $this->create_thumbnail($new_path);

            // alles in 1 transactie
            $this->db->trans_start();

            $url = 'assets/img/profiel_fotos/' . $new_file;

            $insert_foto_sql = 'INSERT INTO Foto(url, titel, beschrijving, profiel_id) VALUES(?,?,?,?)';
            $update_profiel_sql = 'UPDATE Profiel SET profiel_foto_id = ? WHERE pid = ?';

            if ($this->input->post('titel') === NULL) {
                throw new InvalidArgumentException('Titel is verplicht');
            }
            // insert foto
            $this->db->query($insert_foto_sql,
                array($url,
                    $this->input->post('titel'),
                    $this->input->post('beschrijving'),
                    $profiel_id));
            // get foto id
            $query = $this->db->query('SELECT last_insert_rowid() as id;');
            $foto_id = $query->row()->id;

            // update profiel
            $this->db->query($update_profiel_sql, array($foto_id, $profiel_id));

            // commit
            $this->db->trans_complete();
        } else {
            throw new Exception('Kon foto niet verplaatsen');
        }
    }

    /**
     * @param $foto_id int
     * @return bool TRUE on success otherwise FALSE
     */
    private function delete_from_file_path($foto_id)
    {
        $foto_id = intval($foto_id);
        // geen placeholders deleten
        if($foto_id === 1 || $foto_id === 2) {
            return FALSE;
        }
        $query = $this->db->get_where('Foto', 'fid = ' . ($foto_id));
        if (!$query) return FALSE;

        $path = $query->row()->url;
        // unlink is delete file functie in PHP
        $main = unlink($path);
        $thumb = unlink(str_replace('.jpg', '_small.jpg', $path));

        return $main && $thumb;
    }

    /**
     * @param $path string path to foto
     * @return bool true on success
     * @throws Exception if foto resize fails
     */
    private function resize_foto($path)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $path;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 500;
        $config['height'] = 500;

        $this->image_lib->initialize($config);
        if ($this->image_lib->resize()) {
            return TRUE;
        } else {
            throw new Exception($this->image_lib->display_errors());
        }
    }

    /**
     * @param $path string path to foto
     * @return bool true on success
     * @throws Exception if foto resize fails
     */
    private function create_thumbnail($path)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $path;
        $config['create_thumb'] = TRUE;
        $config['thumb_marker'] = '_small';
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 200;
        $config['height'] = 200;

        $this->image_lib->initialize($config);
        if ($this->image_lib->resize()) {
            return TRUE;
        } else {
            throw new Exception($this->image_lib->display_errors());
        }
    }

    private function generate_file_name($file)
    {
        return random_string('alnum', 32) . $file['file_ext'];
    }
}
