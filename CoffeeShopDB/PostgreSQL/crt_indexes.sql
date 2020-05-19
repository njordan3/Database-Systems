/*
 Use this command to create an index on:
 
   *  one or more columns of a table, a partitioned table, or a
      cluster
   *  one or more scalar typed object attributes of a table or a
      cluster
   *  a nested table storage table for indexing a nested table column
 
 An index is a schema object that contains an entry for each value
 that appears in the indexed column(s) of the table or cluster and
 provides direct, fast access to rows. A partitioned index consists
 of partitions containing an entry for each value that appears in the
 indexed column(s) of the table.

Postgre Syntax:
CREATE [ UNIQUE ] INDEX [ CONCURRENTLY ] [ [ IF NOT EXISTS ] name ] ON table_name 
       [ USING method ] ( { column_name | ( expression ) } [ COLLATE collation ] 
       [ opclass ] [ ASC | DESC ] [ NULLS { FIRST | LAST } ] [, ...] )
       [ WITH ( storage_parameter = value [, ... ] ) ]
       [ TABLESPACE tablespace_name ]
      [ WHERE predicate ]
 
*/

-- The following creating-tablespace are correct and
-- creating index with tablespace are correct also.
-- However, for easy to export to othter machine,
-- Tbalespace will not created in the application.

--DROP   TABLESPACE  CSidx;
--CREATE TABLESPACE  CSidx  OWNER coffeeshop
--LOCATION 'var/lib/postgresql/coffeeshop/index';

CREATE INDEX IF NOT EXISTS idx_department
	ON Department(DepartmentID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_employee
	ON Employee(EmployeeID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_worksfor
	ON Works_for(DepartmentID,EmployeeID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_porder
	ON POrder(POrderID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_ingredients
	ON Ingredients(IngredientID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_supplier
	ON Supplier(SupplierID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_supplies
	ON Supplies(SupplierID,IngredientID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_coffeeshop
	ON CoffeeShop(ShopID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_hires
	ON Hires(ShopID,EmployeeID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_inventory
	ON Inventory(ItemID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_documents
	ON Documents(IngredientID,ItemID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_customer
	ON Customer(CustomerID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_menuitem
	ON Menu_item(ProductID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_sorder
	ON SOrder(SOrderID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_payedto
	ON Payed_to(SOrderID,EmployeeID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_fills
	ON Fills(SOrderID,EmployeeID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_uses
	ON Uses(IngredientID,ProductID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_has
	ON Has(ProductID,SOrderID)
	TABLESPACE  CSidx
;

CREATE INDEX IF NOT EXISTS idx_payment
	ON Payment(POrderID)
	TABLESPACE  CSidx
;
