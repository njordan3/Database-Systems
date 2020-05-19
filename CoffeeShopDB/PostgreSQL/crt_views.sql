CREATE OR REPLACE VIEW employeeInfo AS
SELECT h.ShopID, e.EmployeeID, e.ManagerID, e.Name, d.Department_name, e.Sex, e.Birth_date, h.Hire_date
FROM Employee e INNER JOIN Hires h
ON e.EmployeeID = h.EmployeeID
INNER JOIN Works_for w
ON e.EmployeeID = w.EmployeeID
INNER JOIN Department d
ON w.DepartmentID = d.DepartmentID;

CREATE OR REPLACE VIEW salesInfo AS
SELECT h.ShopID,
	Orders_filled.amount "Filled",
	Orders_payed_to.amount "Taken"
FROM Employee emp INNER JOIN Hires h
ON emp.EmployeeID = h.EmployeeID
LEFT JOIN (
	SELECT e.EmployeeID, COUNT(f.SOrderID) amount
	FROM Employee e INNER JOIN Fills f
	ON f.EmployeeID = e.EmployeeID
	GROUP BY e.EmployeeID ) as Orders_filled
ON Orders_filled.EmployeeID = emp.EmployeeID
LEFT JOIN (
	SELECT e.EmployeeID, COUNT(p.SOrderID) amount
	FROM Employee e INNER JOIN Payed_to p
	ON p.EmployeeID = e.EmployeeID
	GROUP BY e.EmployeeID ) as Orders_payed_to
ON Orders_payed_to.EmployeeID = emp.EmployeeID
;

CREATE OR REPLACE VIEW inventoryInfo AS
SELECT e.EmployeeID, inv.ItemID, d.Purchase_amount,
	d.Sale_amount, d.Left_over, inv.Unaccounted_for,
	inv.Period, inv.Date_checked
FROM Employee e INNER JOIN Inventory inv
ON e.EmployeeID = inv.EmployeeID
INNER JOIN Documents d
ON d.ItemID = inv.ItemID
;

CREATE OR REPLACE VIEW sorderInfo AS
SELECT s.sorderid,c.customer_name, hire.ShopID, 
h.product_quantity, h.special_instr, m.product_name, m.size
FROM Customer c INNER JOIN SOrder s
ON c.CustomerID = s.CustomerID
INNER JOIN HAS h
ON h.SOrderID = s.SOrderID
INNER JOIN Menu_item m
ON m.ProductID = h.ProductID
INNER JOIN payed_to p
ON p.SOrderID = s.SOrderID
INNER JOIN Employee e
ON e.EmployeeID = p.EmployeeID
INNER JOIN Hires hire
ON hire.EmployeeID = e.EmployeeID
INNER JOIN (
	SELECT p.SOrderID
	FROM Payed_to p
	WHERE p.SOrderID NOT IN (
		SELECT f.SOrderID
		FROM Fills f
	)) AS not_filled
ON S.SOrderID = not_filled.SOrderID
;
	
CREATE OR REPLACE VIEW filledSOrderInfo AS
SELECT s.sorderid,c.customer_name, hire.ShopID, 
h.product_quantity, h.special_instr, m.product_name, m.size
FROM Customer c INNER JOIN SOrder s
ON c.CustomerID = s.CustomerID
INNER JOIN HAS h
ON h.SOrderID = s.SOrderID
INNER JOIN Menu_item m
ON m.ProductID = h.ProductID
INNER JOIN payed_to p
ON p.SOrderID = s.SOrderID
INNER JOIN Employee e
ON e.EmployeeID = p.EmployeeID
INNER JOIN Hires hire
ON hire.EmployeeID = e.EmployeeID
INNER JOIN (
	SELECT p.SOrderID
	FROM Payed_to p
	WHERE p.SOrderID IN (
		SELECT f.SOrderID
		FROM Fills f
	)) AS not_filled
ON S.SOrderID = not_filled.SOrderID
;
		
CREATE OR REPLACE VIEW receiptInfo AS
SELECT s.sorderid, c.customer_name, h.product_quantity,
h.special_instr, m.product_name, m.size, m.store_price,
cashier.Name, barista.n, cashier.pay_info, cashier.pay_date,
barista.time_delivered, cashier.ShopID, cashier.Shop_address,
cashier.Phone_num, s.Discount
FROM Customer c INNER JOIN SOrder s
ON c.CustomerID = s.CustomerID
INNER JOIN (
	SELECT p.*, e.Name, c.*
	FROM Employee e INNER JOIN Payed_to p
	ON e.EmployeeID = p.EmployeeID
	INNER JOIN Hires h
	ON h.EmployeeID = e.EmployeeID
	INNER JOIN CoffeeShop c
	ON c.ShopID = h.ShopID
	) AS cashier
ON cashier.SOrderID = s.SOrderID
INNER JOIN (
	SELECT f.SOrderID, e.Name n, f.Time_delivered
	FROM Employee e INNER JOIN Fills f
	ON e.EmployeeID = f.EmployeeID
	) AS barista
ON barista.SOrderID = s.SOrderID
INNER JOIN Has h
ON h.SOrderID = s.SOrderID
INNER JOIN Menu_item m
ON m.ProductID = h.ProductID
;
		
/* ====[QUERIES FROM PHASE 2]==== */

/*
CREATE OR REPLACE VIEW Q1 AS
SELECT DISTINCT c.customer_name, c.customerID
FROM SOrder s1 INNER JOIN SOrder s2
ON (s1.SOrderID != s2.SOrderID
	AND s1.ProductID = s2.ProductID
	AND s1.item_quantity = s2.item_quantity
	AND s1.special_instr = s2.special_instr)
INNER JOIN Customer c
ON (s1.CustomerID = c.CustomerID AND s2.customerID = c.customerID)
;

CREATE OR REPLACE VIEW Q2 AS
SELECT m.product_name product, COUNT(m.product_name) purchases
FROM menu_item m INNER JOIN sorder s
ON m.productid = s.productid
GROUP BY m.product_name
ORDER BY purchases DESC
LIMIT 1;

CREATE OR REPLACE VIEW Q3 AS
SELECT * 
FROM SOrder 
WHERE Total > 20
;

CREATE OR REPLACE VIEW Q4 AS
SELECT * 
FROM SOrder 
WHERE Is_online = TRUE
;

CREATE OR REPLACE VIEW Q5 AS
SELECT * 
FROM Customer 
WHERE Membership_status = TRUE
;

CREATE OR REPLACE VIEW Q6 AS
SELECT DISTINCT e.*
FROM Employee e INNER JOIN Checks c
ON e.EmployeeID = c.EmployeeID
INNER JOIN Checks h
ON (c.Date_checked != h.Date_checked AND c.employeeid = h.employeeid)
WHERE (EXTRACT(MONTH FROM c.Date_checked) = EXTRACT(MONTH FROM h.Date_checked)+1)
AND (EXTRACT(YEAR FROM c.Date_checked) = EXTRACT(YEAR FROM h.date_checked))
AND e.salary IS NOT NULL
AND e.hourly_wage IS NULL
;


CREATE OR REPLACE VIEW Q7 AS
SELECT c.*
FROM Customer c INNER JOIN SOrder s 
ON c.CustomerID = s.CustomerID 
WHERE s.Tip > 0 AND s.Is_online = TRUE
;

CREATE OR REPLACE VIEW Q8 AS
SELECT a.Customer             
FROM (
        SELECT c.customer_name Customer, COUNT(customer_name) Purchases
        FROM customer c INNER JOIN sorder s
        ON c.customerid = s.customerid
        GROUP BY c.customer_name ) AS a
WHERE a.Purchases = 1
;


CREATE OR REPLACE VIEW Q9 AS
SELECT a1.name Employee, a2.customer_name Customer
FROM ( 
	SELECT e.name, e.employeeid, s.sorderid
	FROM employee e FULL OUTER JOIN fills f
	ON e.employeeid = f.employeeid
	INNER JOIN sorder s
	ON s.sorderid = f.sorderid ) AS a1
INNER JOIN (
	SELECT c.customer_name, sorderid
	FROM customer c FULL OUTER JOIN sorder s
	ON c.customerid = s.customerid
	WHERE c.customer_name = 'Travers Valente' ) as a2
ON a1.sorderid = a2.sorderid
;

CREATE OR REPLACE VIEW Q10 AS
SELECT c.customer_name
FROM Customer c INNER JOIN SOrder s
ON c.CustomerID = s.CustomerID
AND c.Membership_status = TRUE
AND (s.sDate > '2019-12-31' OR s.sDate < '2019-01-01')
;*/
