<?php

class Model_item extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getItemData($itemId = null)
    {
        if ($itemId) {
            $sql = "SELECT 
                        it.intItemID,
                        it.vcItemName,
                        mu.intMeasureUnitID,
                        mu.vcMeasureUnit,
                        m.vcMainCategory, 
                        s.vcSubCategory,
                        COALESCE(SUM(gd.decAvailableQty),0) AS decStockInHand,
                        it.decReOrderLevel,
                        it.decUnitPrice,
                        REPLACE(it.rv,' ','-') as rv 
                    FROM item as it
                    inner join measureunit as mu on mu.intMeasureUnitID = it.intMeasureUnitID
                    inner join subcategory as s on it.intSubCategoryID = s.intSubCategoryID
                    inner join maincategory m on s.intMainCategoryID = m.intMainCategoryID
                    left outer join grndetail as gd on it.intItemID = gd.intItemID
                    WHERE it.IsActive = 1 AND it.intItemID = ? 
                    GROUP BY it.intItemID";

            $query = $this->db->query($sql, array($itemId));
            return $query->row_array();
        }

        $sql = "SELECT 
                    it.intItemID,
                    it.vcItemName,
                    mu.intMeasureUnitID,
                    mu.vcMeasureUnit,
                    m.vcMainCategory, 
                    s.vcSubCategory, 
                    COALESCE(SUM(gd.decAvailableQty),0) AS decStockInHand,
                    it.decReOrderLevel,
                    IFNULL(it.decUnitPrice,'N/A') AS decUnitPrice,
                    REPLACE(it.rv,' ','-') as rv 
                FROM item as it
                inner join measureunit as mu on mu.intMeasureUnitID = it.intMeasureUnitID
                inner join subcategory as s on it.intSubCategoryID = s.intSubCategoryID
                inner join maincategory m on s.intMainCategoryID = m.intMainCategoryID
                left outer join grndetail as gd on it.intItemID = gd.intItemID
                where  it.IsActive = 1
                GROUP BY it.intItemID
                order by it.vcItemName asc";
        $query = $this->db->query($sql, array(1));
        return $query->result_array();
    }

    public function getStocktItemData()
    {
                $sql = "SELECT    I.intItemID,
                            I.vcItemName,
                            MU.intMeasureUnitID,
                            MU.vcMeasureUnit,
                            M.vcMainCategory, 
                            S.vcSubCategory, 
                            COALESCE(SUM(GD.decAvailableQty),0) AS decStockInHand,
                            I.decReOrderLevel,
                            IFNULL(I.decUnitPrice,'N/A') AS decUnitPrice,
                            REPLACE(I.rv,' ','-') as rv  FROM
            grnheader AS GH 
            INNER JOIN grndetail AS GD ON GH.intGRNHeaderID = GD.intGRNHeaderID AND GH.intApprovedBy is not null
            RIGHT OUTER JOIN item AS I ON GD.intItemID = I.intItemID
            inner join measureunit as MU on MU.intMeasureUnitID = I.intMeasureUnitID
            inner join subcategory as S on I.intSubCategoryID = S.intSubCategoryID
            inner join maincategory M on S.intMainCategoryID = M.intMainCategoryID
            where  I.IsActive = 1 
            GROUP BY I.intItemID
            ORDER BY I.vcItemName asc";
          $query = $this->db->query($sql, array(1));
        return $query->result_array();
    }

    public function getStockAvailableItemData()
    {
        $sql = "SELECT 
                it.intItemID,
                it.vcItemName,
                mu.intMeasureUnitID,
                mu.vcMeasureUnit,
                m.vcMainCategory, 
                s.vcSubCategory, 
                COALESCE(SUM(gd.decAvailableQty),0) AS decStockInHand,
                it.decReOrderLevel,
                IFNULL(it.decUnitPrice,'N/A') AS decUnitPrice,
                REPLACE(it.rv,' ','-') as rv 
            FROM item as it
            inner join measureunit as mu on mu.intMeasureUnitID = it.intMeasureUnitID
            inner join subcategory as s on it.intSubCategoryID = s.intSubCategoryID
            inner join maincategory m on s.intMainCategoryID = m.intMainCategoryID
            left outer join grndetail as gd on it.intItemID = gd.intItemID
            left outer join grnheader as gh on gd.intGRNHeaderID = gh.intGRNHeaderID and  gh.intApprovedBy is not null
            where  it.IsActive = 1 AND gd.decAvailableQty > 0 
            GROUP BY it.intItemID
            order by it.vcItemName asc";
        $query = $this->db->query($sql, array(1));
        return $query->result_array();
    }


    // public function getItemDataByItemTypeID($itemTypeId)
    // {
    //     if ($itemTypeId == 0) { //ALL Item
    //         $sql = "SELECT 
    //                     it.intItemID,
    //                     it.vcItemName,
    //                     mu.intMeasureUnitID,
    //                     mu.vcMeasureUnit,
    //                     IFNULL(it.decStockInHand,'N/A') AS decStockInHand,
    //                     it.decReOrderLevel,
    //                     IFNULL(it.decUnitPrice,'N/A') AS decUnitPrice,
    //                     it.rv 
    //                 FROM item as it
    //                 inner join measureunit as mu on mu.intMeasureUnitID = it.intMeasureUnitID
    //                 where  IsActive = 1
    //                 order by it.vcItemName asc";
    //         $query = $this->db->query($sql, array(1));
    //         return $query->result_array();
    //     }
    //     if ($itemTypeId) {
    //         $sql = "SELECT 
    //                     it.intItemID,
    //                     it.vcItemName,
    //                     mu.intMeasureUnitID,
    //                     mu.vcMeasureUnit,
    //                     IFNULL(it.decStockInHand,'N/A') AS decStockInHand,
    //                     it.decReOrderLevel,
    //                     IFNULL(it.decUnitPrice,'N/A') AS decUnitPrice,
    //                     it.rv 
    //                 FROM item as it
    //                 INNER JOIN measureunit AS mu ON mu.intMeasureUnitID = it.intMeasureUnitID
    //                 WHERE IsActive = 1 AND t.intItemTypeID = ? 
    //                 ORDER BY it.vcItemName ASC ";
    //         $query = $this->db->query($sql, array($itemTypeId));
    //         return $query->result_array();
    //     }
    // }

    public function getOnlyCmbItemData()
    {
        $sql = "SELECT    I.intItemID,
                I.vcItemName,
                    MU.intMeasureUnitID,
                    MU.vcMeasureUnit,
                    M.vcMainCategory, 
                    S.vcSubCategory, 
                    COALESCE(SUM(GD.decAvailableQty),0) AS decStockInHand,
                    I.decReOrderLevel,
                    IFNULL(I.decUnitPrice,'N/A') AS decUnitPrice,
                    REPLACE(I.rv,' ','-') as rv  FROM
            grnheader AS GH 
            INNER JOIN grndetail AS GD ON GH.intGRNHeaderID = GD.intGRNHeaderID AND GH.intApprovedBy is not null
            RIGHT OUTER JOIN item AS I ON GD.intItemID = I.intItemID
            inner join measureunit as MU on MU.intMeasureUnitID = I.intMeasureUnitID
            inner join subcategory as S on I.intSubCategoryID = S.intSubCategoryID
            inner join maincategory M on S.intMainCategoryID = M.intMainCategoryID
            where  I.IsActive = 1 
            GROUP BY I.intItemID
        ORDER BY I.vcItemName asc";
        $query = $this->db->query($sql, array(1));
        return $query->result_array();
    }

    // public function getOnlyFinishItemDataByNotConfig($intCuttingOrderHeaderID)
    // {
    //     $sql = "SELECT 
    //                 intItemID,
    //                 vcItemName 
    //             FROM item 
    //             WHERE IsActive = 1 
    //             AND intItemTypeID = 2 
    //             AND intItemID NOT IN (SELECT  IT.intItemID
    //                                 FROM CuttingOrderHeader CH
    //                                 INNER JOIN cuttingorderdetail CD ON CH.intCuttingOrderHeaderID = CD.intCuttingOrderHeaderID
    //                                 INNER JOIN item AS IT ON CD.intItemID = IT.intItemID
    //                                 WHERE CH.IsActive = 1 AND IT.IsActive = 1 
    //                                 AND CH.intCuttingOrderHeaderID = ?)";
    //     $query = $this->db->query($sql, array($intCuttingOrderHeaderID));
    //     return $query->result_array();
    // }

    public function getItemDetailsByCustomerID($ItemID, $customerID)
    {
        $sql = "SELECT 
                    it.intItemID,
                    it.vcItemName,
                    mu.intMeasureUnitID,
                    mu.vcMeasureUnit,
                    COALESCE(SUM(CAST(gd.decAvailableQty AS INT)),0) AS decStockInHand,
                    it.decReOrderLevel,
                    IFNULL(cc.decUnitPrice,it.decUnitPrice) AS decUnitPrice,
                    REPLACE(it.rv,' ','-') as rv 
                FROM item as it
                inner join measureunit as mu on mu.intMeasureUnitID = it.intMeasureUnitID
                left outer join grndetail as gd on it.intItemID = gd.intItemID
                left outer join grnheader as gh on gd.intGRNHeaderID = gh.intGRNHeaderID
                left outer join customerpriceconfig as cc on it.intItemID = cc.intItemID and cc.intCustomerID = ? 
                where it.intItemID = ? and gh.intApprovedBy is not null
                group by it.intItemID";
        $query = $this->db->query($sql, array($customerID, $ItemID));
        return $query->row_array();
    }

    public function getfirstInFirstOut($intItemID)
    {
        $sql = "SELECT intItemID, vcItemName, decAvailableQty, decUnitPrice, dtCreatedDate, intGrnDetailID, rv FROM stock_balances_view WHERE intItemID = ?";
        $query = $this->db->query($sql, array($intItemID));
        return $query->result_array();
    }

    // public function getAllItemData()
    // {
    //     $sql = "SELECT 
    //                 it.intItemID,
    //                 it.vcItemName,
    //                 mu.intMeasureUnitID,
    //                 mu.vcMeasureUnit,
    //                 it.decStockInHand,
    //                 it.decReOrderLevel,
    //                 it.decUnitPrice,
    //                 REPLACE(it.rv,' ','-') as rv 
    //             FROM item as it
    //             inner join measureunit as mu on mu.intMeasureUnitID = it.intMeasureUnitID
    //             where  IsActive = 1 
    //             order by it.vcItemName asc";
    //     $query = $this->db->query($sql, array(1));
    //     return $query->result_array();
    // }

    // public function getBranchStockItems($id)
    // {
    //     if ($id) {
    //         $sql = "SELECT BS.intBranchStockID, BS.intBranchID, BS.intItemID, IT.vcItemName, BS.decStockInHand, BS.decReOrderLevel,REPLACE(BS.rv,' ','-') as rv FROM branchstock AS BS
    //     INNER JOIN item AS IT ON BS.intItemID = IT.intItemID
    //     WHERE BS.intBranchID = ? ";
    //         $query = $this->db->query($sql, array(1));
    //         return $query->row_array();
    //     }

    //     $sql = "SELECT BS.intBranchStockID, BS.intBranchID, BS.intItemID, IT.vcItemName, BS.decStockInHand, BS.decReOrderLevel, REPLACE(BS.rv,' ','-') as rv FROM branchstock AS BS
    //     INNER JOIN item AS IT ON BS.intItemID = IT.intItemID";
    //     $query = $this->db->query($sql);
    //     return $query->result_array();
    // }

    public function create($data)
    {
        if ($data) {
            $insert = $this->db->insert('item', $data);
            return ($insert == true) ? true : false;
        }
    }

    public function chkexists($id = null)
    {
        if ($id) {
            $sql = "SELECT EXISTS(SELECT intItemID  FROM grndetail WHERE intItemID = ?) AS value";
            $query = $this->db->query($sql, array($id));
            return $query->result_array();
        }
    }

    public function update($data, $id)
    {
        if ($data && $id) {
            $this->db->where('intItemID', $id);
            $update = $this->db->update('item', $data);
            return ($update == true) ? true : false;
        }
    }

    public function insertItemHitory($intEnteredBy, $id)
    {
        $this->db->trans_start();
        $sql = "SELECT intItemID, vcItemName, intMeasureUnitID, dtCreatedDate, intUserID, IsActive, decReOrderLevel, intSubCategoryID, decUnitPrice, dtLastModifiedDate FROM item WHERE intitemID = ? ";
        $query = $this->db->query($sql, array($id));
        if ($query->num_rows()) {
            $this->db->insert('item_his', $query->row_array());
            $insert_id = $this->db->insert_id();
            $this->db->where('intItem_hisID', $insert_id);
            $update = $this->db->update('item_his', $intEnteredBy);
            $this->db->trans_complete();
            return ($update == true) ? true : false;
        }
    }

    public function chkRv($id = null)
    {
        if ($id) {
            $sql = "SELECT REPLACE(rv,' ','-') as rv FROM `item` WHERE intItemID = ?";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }
    }

    public function chkStockViewRv($id = null)
    {
        if ($id) {
            $sql = "SELECT REPLACE(rv,' ','-') as rv FROM GRNDetail  WHERE intGRNDetailID  = ?";
            $query = $this->db->query($sql, array($id));
            return $query->row_array();
        }
    }

    public function remove($id)
    {
        if ($id) {
            $data = [
                'IsActive' => '0',
            ];
            $this->db->where('intItemID', $id);
            $delete = $this->db->update('item', $data);
            return ($delete == true) ? true : false;
        }
    }

    public function getMainCatWiseSubCat($MainCatID)
    {
        $sql = "SELECT S.intSubcategoryID ,M.vcMainCategory,S.vcSubcategory FROM maincategory AS M
        INNER JOIN subcategory AS S ON M.intMainCategoryID = S.intMainCategoryID
        WHERE M.intMainCategoryID = ?";
        $query = $this->db->query($sql, array($MainCatID));
        return $query->result_array();
    }
}
