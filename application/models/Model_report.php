<?php
class Model_report extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getIssueWiseCostAndProfitData($IssueHeaderID)
    {
            $sql = "SELECT 
            IH.vcIssueNo,
            IT.vcItemName,
            GD.decUnitPrice AS GRNValue,
            ID.decUnitPrice AS IssuedValue,
            FLOOR(ID.decIssueQty) AS IssuedQty,
            FLOOR(ID.decDiscountPercentage) AS IssuedDiscountPercentage,
            ID.decDiscountedPrice AS IssuedAmount,	
            (GD.decUnitPrice * ID.decIssueQty) AS GRNAmount, 
            (ID.decDiscountedPrice - (GD.decUnitPrice * ID.decIssueQty)) AS ProfitAmount
        FROM Issueheader AS IH 
        INNER JOIN IssueDetail AS ID ON IH.intIssueHeaderID = ID.intIssueHeaderID
        INNER JOIN GrnDetail AS GD ON ID.intGRNDetailID = GD.intGRNDetailID
        INNER JOIN Item AS IT ON ID.intItemID = IT.intItemID
        INNER JOIN PaymentType AS PY ON IH.intPaymentTypeID = PY.intPaymentTypeID
        INNER JOIN Salesrep AS SR ON IH.intSalesRepID = SR.intSalesRepID
        WHERE IH.intIssueHeaderID = ?
        ORDER BY IH.vcIssueNo";
        $query = $this->db->query($sql, array($IssueHeaderID));
        return $query->result_array();
    }
}
