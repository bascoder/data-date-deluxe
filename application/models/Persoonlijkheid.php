<?php

/**
 * @property CI_DB_query_builder|CI_DB_pdo_driver db
 */
class Persoonlijkheid extends CI_Model
{
    public function query_all()
    {
        $query = $this->db->from('Persoonlijkheids_type')->order_by('name')->get();

        $typen = [];

        $row = $query->row();
        do {
            array_push($typen, $row);
        } while ($row = $query->next_row());

        return $typen;
    }

    public function add_personality($answers)
    {
        //$this->output->enable_profiler(TRUE);
        $characteristics = array('E' => 50, 'N' => 50, 'T' => 50, 'J' => 50);
        foreach ($answers as $key => $value) {
            $characteristics[$key[0]] += $value;
        }
        foreach ($characteristics as $key => $value) {
            if($value > 100 || $value < 0){
                return FALSE;
            }
        }
        $sql = "INSERT INTO Persoonlijkheids_type(ptid,pcid,eType, nType, tType, jType) VALUES (?,?,?,?,?,?)";
        $pid = $this->authentication->get_current_profiel()->pid;
        $pcidText = $this->makePersonalityString($characteristics);
        $pcid = $this->db->query("SELECT pcid FROM Persoonlijkheids_categorie WHERE type=?", array($pcidText))->row()->pcid;
        $isRetake = $this->db->query("SELECT * FROM Profiel WHERE persoonlijkheids_type_id=".intval($pid));
        if(null!== $this->db->query("SELECT * FROM Profiel WHERE persoonlijkheids_type_id=".intval($pid))->row()){
            return "Retake";
        }
        try {
            $this->db->query($sql, array(intval($pid),$pcid,$characteristics['E'],$characteristics['N'],$characteristics['T'],$characteristics['J']));
            $this->db->query("UPDATE Profiel SET persoonlijkheids_type_id=? WHERE pid=?",array(intval($pid),intval($pid)));
            return TRUE;
        } catch (Exception $e) {
            return FALSE;
        }
        return FALSE;
    }

    function makePersonalityString($characteristics){
        $start = "";
        if($characteristics['E'] > 50){
            $start .= 'E';
        } else { $start .= 'I';}
        if($characteristics['N'] > 50){
            $start .= 'N';
        } else { $start .= 'S';}
        if($characteristics['T'] > 50){
            $start .= 'T';
        } else { $start .= 'F';}
        if($characteristics['J'] > 50){
            $start .= 'J';
        } else { $start .= 'P';}

        return $start;
    }
}
