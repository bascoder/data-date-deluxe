<?php

/**
 *
 * @property CI_Image_lib image_lib
 */
class Foto extends CI_Model
{
    public function insert_foto($url, $profielId)
    {

    }

    public function insert_profiel_foto($file, $profiel_id)
    {
        $current_path = $file['full_path'];
        $this->resize_foto($current_path);
        $new_file = $this->generate_file_name($file);
        $new_path = 'assets/img/profiel_fotos/' . $new_file;

        if (rename($current_path, $new_path)) {

            // alles in 1 transactie
            $this->db->trans_start();

            $url = asset_url() . 'img/profiel_fotos/' . $new_file;

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

        $this->load->library('image_lib', $config);
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
