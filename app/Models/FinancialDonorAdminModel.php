<?php
class FinancialDonorAdminModel {
    use Model;

    protected $table = 'financial_donors';

    public function getTotalDonors(){
        $result = $this->query("SELECT COUNT(*) AS total FROM $this->table");
        if($result && isset($result[0]->total)){
            return $result[0]->total;
        }
        return 0;
    }
}