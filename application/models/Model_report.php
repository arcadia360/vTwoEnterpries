<?php
class Model_report extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getIssueWiseCostAndProfitData($IssueHeaderID)
    {
        //     $sql = "SELECT 
        //     IH.vcIssueNo,
        //     IT.vcItemName,
        //     GD.decUnitPrice AS GRNValue,
        //     ID.decUnitPrice AS IssuedValue,
        //     FLOOR(ID.decIssueQty) AS IssuedQty,
        //     FLOOR(ID.decDiscountPercentage) AS IssuedDiscountPercentage,
        //     ID.decDiscountedPrice AS IssuedAmount,	
        //     (GD.decUnitPrice * ID.decIssueQty) AS GRNAmount, 
        //     (ID.decDiscountedPrice - (GD.decUnitPrice * ID.decIssueQty)) AS ProfitAmount
        // FROM Issueheader AS IH 
        // INNER JOIN IssueDetail AS ID ON IH.intIssueHeaderID = ID.intIssueHeaderID
        // INNER JOIN GrnDetail AS GD ON ID.intGRNDetailID = GD.intGRNDetailID
        // INNER JOIN Item AS IT ON ID.intItemID = IT.intItemID
        // INNER JOIN PaymentType AS PY ON IH.intPaymentTypeID = PY.intPaymentTypeID
        // INNER JOIN Salesrep AS SR ON IH.intSalesRepID = SR.intSalesRepID
        // WHERE IH.intIssueHeaderID = ?
        // ORDER BY IH.vcIssueNo";
        // $query = $this->db->query($sql, array($IssueHeaderID));
        // return $query->result_array();

        $sql = "SELECT 
                IH.vcIssueNo,
                IT.vcItemName,
                GD.decUnitPrice AS GRNValue,
                ID.decUnitPrice AS IssuedValue,
                FLOOR(ID.decIssueQty) AS IssuedQty,
                FLOOR(ID.decDiscountPercentage) AS IssuedDiscountPercentage,
                ID.decDiscountedPrice AS IssuedAmount,
                (IFNULL(IRD.decReturnQty,0) * IFNULL(IRD.decUnitPrice,0)) AS ReturnedAmount,
                (GD.decUnitPrice * (ID.decIssueQty - IFNULL(IRD.decReturnQty,0))) AS GRNAmount, 
                ((ID.decDiscountedPrice - (IFNULL(IRD.decReturnQty,0) * IFNULL(IRD.decUnitPrice,0))) - (GD.decUnitPrice * (ID.decIssueQty - IFNULL(IRD.decReturnQty,0)))) AS ProfitAmount
            FROM Issueheader AS IH 
            INNER JOIN IssueDetail AS ID ON IH.intIssueHeaderID = ID.intIssueHeaderID
            LEFT OUTER JOIN IssueReturnDetail AS IRD ON ID.intIssueDetailID = IRD.intIssueDetailID
            INNER JOIN GrnDetail AS GD ON ID.intGRNDetailID = GD.intGRNDetailID
            INNER JOIN Item AS IT ON ID.intItemID = IT.intItemID
            INNER JOIN PaymentType AS PY ON IH.intPaymentTypeID = PY.intPaymentTypeID
            INNER JOIN Salesrep AS SR ON IH.intSalesRepID = SR.intSalesRepID
            WHERE IH.intIssueHeaderID = ?
            ORDER BY IH.vcIssueNo";
        $query = $this->db->query($sql, array($IssueHeaderID));
        return $query->result_array();
    }

    public function getGRNWiseCostAndProfitData($FromDate, $ToDate)
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
            SUM(ProfitAmount) AS decProfitTotal
			FROM (
			  SELECT 
				IH.vcIssueNo,
				IT.vcItemName,
				GD.decUnitPrice AS GRNValue,
				ID.decUnitPrice AS IssuedValue,
				FLOOR(ID.decIssueQty) AS IssuedQty,
				FLOOR(ID.decDiscountPercentage) AS IssuedDiscountPercentage,
				ID.decDiscountedPrice AS IssuedAmount,
				(IFNULL(IRD.decReturnQty,0) * IFNULL(IRD.decUnitPrice,0)) AS ReturnedAmount,
				(GD.decUnitPrice * (ID.decIssueQty - IFNULL(IRD.decReturnQty,0))) AS GRNAmount, 
				((ID.decDiscountedPrice - (IFNULL(IRD.decReturnQty,0) * IFNULL(IRD.decUnitPrice,0))) - (GD.decUnitPrice * (ID.decIssueQty - IFNULL(IRD.decReturnQty,0)))) AS ProfitAmount
			  FROM 
				Issueheader AS IH 
				INNER JOIN IssueDetail AS ID ON IH.intIssueHeaderID = ID.intIssueHeaderID
				LEFT OUTER JOIN IssueReturnDetail AS IRD ON ID.intIssueDetailID = IRD.intIssueDetailID
				INNER JOIN GrnDetail AS GD ON ID.intGRNDetailID = GD.intGRNDetailID
				INNER JOIN Item AS IT ON ID.intItemID = IT.intItemID
				INNER JOIN PaymentType AS PY ON IH.intPaymentTypeID = PY.intPaymentTypeID
				INNER JOIN Salesrep AS SR ON IH.intSalesRepID = SR.intSalesRepID
			  WHERE 
				  CAST(IH.dtCreatedDate AS DATE) between '" . $FromDate . "' AND  '" . $ToDate . "' 
          		) AS subquery 
    ) AS A
    ";
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getIssueSummaryData($FromDate, $ToDate, $CustomerID)
    {
        $querylast = "";

        if ($CustomerID == 0) {
            $querylast = " ORDER BY IH.vcIssueNo";
        } else {
            $querylast = " AND IH.intCustomerID =   '" . $CustomerID . "' ORDER BY IH.vcIssueNo";
        }

        $sql = "SELECT 
                IH.vcIssueNo,
                IT.vcItemName,
                GD.decUnitPrice AS GRNValue,
                ID.decUnitPrice AS IssuedValue,
                FLOOR(ID.decIssueQty) AS IssuedQty,
                FLOOR(ID.decDiscountPercentage) AS IssuedDiscountPercentage,
                ID.decDiscountedPrice AS IssuedAmount,
                (IFNULL(IRD.decReturnQty,0) * IFNULL(IRD.decUnitPrice,0)) AS ReturnedAmount,
                (GD.decUnitPrice * (ID.decIssueQty - IFNULL(IRD.decReturnQty,0))) AS GRNAmount, 
                ((ID.decDiscountedPrice - (IFNULL(IRD.decReturnQty,0) * IFNULL(IRD.decUnitPrice,0))) - (GD.decUnitPrice * (ID.decIssueQty - IFNULL(IRD.decReturnQty,0)))) AS ProfitAmount
            FROM Issueheader AS IH 
            INNER JOIN IssueDetail AS ID ON IH.intIssueHeaderID = ID.intIssueHeaderID
            LEFT OUTER JOIN IssueReturnDetail AS IRD ON ID.intIssueDetailID = IRD.intIssueDetailID
            INNER JOIN GrnDetail AS GD ON ID.intGRNDetailID = GD.intGRNDetailID
            INNER JOIN Item AS IT ON ID.intItemID = IT.intItemID
            INNER JOIN PaymentType AS PY ON IH.intPaymentTypeID = PY.intPaymentTypeID
            INNER JOIN Salesrep AS SR ON IH.intSalesRepID = SR.intSalesRepID
            WHERE CAST(IH.dtCreatedDate AS DATE) between '" . $FromDate . "' AND  '" . $ToDate . "'";

        $sql  = $sql . $querylast;
        $query = $this->db->query($sql);
        return $query->result_array();
    }

    public function getCustomerWiseSalesReport($CustomerID,$FromDate,$ToDate)
    {
        $sql = "SELECT 
                IH.vcIssueNo,
                IT.vcItemName,
                GD.decUnitPrice AS GRNValue,
                ID.decUnitPrice AS IssuedValue,
                FLOOR(ID.decIssueQty) AS IssuedQty,
                FLOOR(ID.decDiscountPercentage) AS IssuedDiscountPercentage,
                ID.decDiscountedPrice AS IssuedAmount,
                (IFNULL(IRD.decReturnQty,0) * IFNULL(IRD.decUnitPrice,0)) AS ReturnedAmount,
                (GD.decUnitPrice * (ID.decIssueQty - IFNULL(IRD.decReturnQty,0))) AS GRNAmount, 
                ((ID.decDiscountedPrice - (IFNULL(IRD.decReturnQty,0) * IFNULL(IRD.decUnitPrice,0))) - (GD.decUnitPrice * (ID.decIssueQty - IFNULL(IRD.decReturnQty,0)))) AS ProfitAmount
            FROM Issueheader AS IH 
            INNER JOIN IssueDetail AS ID ON IH.intIssueHeaderID = ID.intIssueHeaderID
            LEFT OUTER JOIN IssueReturnDetail AS IRD ON ID.intIssueDetailID = IRD.intIssueDetailID
            INNER JOIN GrnDetail AS GD ON ID.intGRNDetailID = GD.intGRNDetailID
            INNER JOIN Item AS IT ON ID.intItemID = IT.intItemID
            INNER JOIN PaymentType AS PY ON IH.intPaymentTypeID = PY.intPaymentTypeID
            INNER JOIN Salesrep AS SR ON IH.intSalesRepID = SR.intSalesRepID
            INNER JOIN customer AS C ON IH.intCustomerID = C.intCustomerID
            WHERE C.intCustomerID = '" . $CustomerID . "' AND CAST(IH.dtCreatedDate AS DATE) between '" . $FromDate . "' AND  '" . $ToDate . "' 
            ORDER BY IH.vcIssueNo";
            
        $query = $this->db->query($sql);
        return $query->result_array();
    }

}
