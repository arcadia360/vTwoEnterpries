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

    public function getGRNWiseCostAndProfitData($FromDate,$ToDate)
    {
    $sql = "SELECT SUM(decGrandTotal) AS decGrandTotal, SUM(decIssueTotal) AS decIssueTotal, SUM(decProfitTotal) AS decProfitTotal 
    FROM(
            SELECT SUM(decGrandTotal) AS  decGrandTotal, 0 AS decIssueTotal, 0 AS decProfitTotal
            FROM GRNHeader AS GH
            WHERE GH.dtApprovedOn IS NOT NULL AND CAST(GH.dtApprovedOn AS DATE) between '" . $FromDate . "' AND  '" . $ToDate . "'
    
            UNION
            
            SELECT 
            0 AS decGrandTotal,
            SUM(IH.decGrandTotal- IFNULL(RH.decTotal,0)) AS decIssueTotal,
            0 AS decProfitTotal
            FROM IssueHeader AS IH
            LEFT OUTER JOIN IssueReturnHeader AS RH ON IH.intIssueHeaderID = RH.intIssueHeaderID 
            WHERE CAST(IH.dtCreatedDate AS DATE) between '" . $FromDate . "' AND  '" . $ToDate . "'
            
            UNION 
    
            SELECT  
            0 AS decGrandTotal,
            0 AS decIssueTotal,
            (SUM(ID.decDiscountedPrice) - SUM((IFNULL(IRD.decUnitPrice,0) * IFNULL(IRD.decReturnQty,0)))) - SUM(ID.decIssueQty  * GD.decUnitPrice) AS decProfitTotal
            FROM IssueDetail AS ID
            INNER JOIN IssueHeader AS IH ON ID.intIssueHeaderID = IH.intIssueHeaderID
            LEFT OUTER JOIN IssueReturnDetail AS IRD ON ID.intIssueDetailID = IRD.intIssueDetailID
            INNER JOIN GRNDetail AS GD ON ID.intGRNDetailID = GD.intGRNDetailID
            WHERE CAST(IH.dtCreatedDate AS DATE) between '" . $FromDate . "' AND  '" . $ToDate . "'
    ) AS A
    ";
    $query = $this->db->query($sql);
    return $query->result_array();
    }
}
