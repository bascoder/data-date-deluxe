<?php

/**
 * @property CI_DB_query_builder|CI_DB_pdo_driver db
 */
class Merk extends CI_Model
{
    public function query_all()
    {
        $query = $this->db->from('Merk')->order_by('merk')->get();

        $merken = [];

        $row = $query->row();
        do {
            array_push($merken, $row);
        } while ($row = $query->next_row());

        return $merken;
    }

    /**
     * Verwijdert alle merk voorkeuren van huidig profiel en vervangt ze met de invoer array
     * @param $merken array met merken
     * @return bool TRUE voor success
     * @throws Exception|Error on error
     */
    public function replace_all_merk_voorkeuren($merken)
    {
        $pid = $this->validate_huidig_profiel();

        // in 1 transactie
        $this->db->trans_start();
        try {
            //eerst delete huidige voorkeuren
            $this->db->delete('Merk_voorkeur', 'profiel_id = ' . $pid);

            // voor elk merk doe een insert
            foreach ($merken as $merk) {
                if (!isset($merk) || !isset($merk->mid) || intval($merk->mid) === 0) {
                    // abort en throw
                    $this->db->trans_rollback();
                    throw new InvalidArgumentException('Expect mid for each Merk object');
                }

                // anders voer de insert uit
                $this->db->insert('Merk_voorkeur', array(
                    'merk_id' => intval($merk->mid),
                    'profiel_id' => intval($pid)
                ));
            }
        } catch (Error $e) {
            // gewoon opnieuw throwen, het gaat erom dat het finally block wordt aangeroepen
            throw $e;
        } finally {
            // rollback of commit afhankelijk van de status
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return FALSE;
            } else {
                $this->db->trans_commit();
                return TRUE;
            }
        }
    }

    /**
     * @return int
     * @throws Exception
     */
    private function validate_huidig_profiel()
    {
        $profiel = current_profiel();
        if (!$profiel || !isset($profiel->pid)) {
            throw new Exception('Moet ingelogd zijn');
        }
        $pid = intval($profiel->pid);
        if ($pid === 0) {
            throw new Exception('Sessie is corrupt');
        }
        return $pid;
    }
}
