/* 	When the user coffeeshop is created, the default TABLESPACE is
  	defined. Therefore no tablespace specification is necessary
  	when the tables are created in Gradebook account.
*/

\c coffeeshop;

CREATE TYPE sex AS ENUM ('Male', 'Female');
CREATE TYPE job AS ENUM ('Manager', 'Cashier', 'Barista');
CREATE TYPE month AS ENUM ('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
CREATE TYPE payment_type AS ENUM ('Credit', 'Debit', 'Cash');
CREATE TYPE size_type AS ENUM ('Small', 'Medium', 'Large');

CREATE TABLE IF NOT EXISTS Department (
	DepartmentID	SERIAL NOT NULL,
	Department_name	job NOT NULL,
	CONSTRAINT pk_department PRIMARY KEY (DepartmentID),
	CONSTRAINT uniq_department_name UNIQUE (Department_name)
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Employee (
	EmployeeID 	SERIAL NOT NULL,
	Name 		VARCHAR(30) NOT NULL,
	SSN 		VARCHAR(11) NOT NULL,
	Address 	VARCHAR(30) NOT NULL,
	Birth_date 	DATE NOT NULL,
	Sex 		sex NOT NULL,
	ManagerID 	INTEGER,
	CONSTRAINT pk_employee PRIMARY KEY (EmployeeID),
	CONSTRAINT uniq_employee UNIQUE (Address, SSN),
	CONSTRAINT chk_employee_bd CHECK (Birth_date < '2003-01-01')
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Works_for (
	DepartmentID	INTEGER NOT NULL,
	EmployeeID	INTEGER NOT NULL,
	Dept_start	DATE NOT NULL,
	Dept_end	DATE,
	Wage		Numeric (4, 2),
	Salary		INTEGER,
	CONSTRAINT pk_worksfor PRIMARY KEY (DepartmentID, EmployeeID),
	CONSTRAINT uniq_worksfor_emp UNIQUE (EmployeeID),
	CONSTRAINT fk_worksfor_dept FOREIGN KEY (DepartmentID)
						REFERENCES Department(DepartmentID)
						ON DELETE CASCADE
						ON UPDATE CASCADE,
	CONSTRAINT fk_worksfor_emp FOREIGN KEY (EmployeeID)
						REFERENCES Employee(EmployeeID)
						ON DELETE CASCADE
						ON UPDATE CASCADE,
	CONSTRAINT chk_worksfor_sdate CHECK (Dept_start > '2015-10-22'),
	CONSTRAINT chk_worksfor_edate CHECK (Dept_end > Dept_start)
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS POrder (
	POrderID	SERIAL NOT NULL,
	EmployeeID	INTEGER NOT NULL,
	Received	BOOLEAN NOT NULL,
	CONSTRAINT pk_porder PRIMARY KEY (POrderID),
	CONSTRAINT fk_porder_emp FOREIGN KEY (EmployeeID)
				      	REFERENCES Employee(EmployeeID)
				      	ON DELETE CASCADE
				     	ON UPDATE CASCADE
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Ingredients (
	IngredientID	SERIAL NOT NULL,
	POrderID	INTEGER NOT NULL,
	Price		Numeric (6,2) NOT NULL,
	Quantity	VARCHAR(10) NOT NULL, -- measured as a mass
	CONSTRAINT pk_ingredients PRIMARY KEY (IngredientID),
	CONSTRAINT chk_ingredients_price CHECK (Price > 0),
	CONSTRAINT fk_ingredients_porder FOREIGN KEY (POrderID)
				      	REFERENCES POrder(POrderID)
				      	ON DELETE CASCADE
				     	ON UPDATE CASCADE
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Supplier (
	SupplierID	SERIAL NOT NULL,
	Supplier_name	VARCHAR(30) NOT NULL,
	Address		VARCHAR(30) NOT NULL,
	Phone_num	VARCHAR(12) NOT NULL,
	CONSTRAINT pk_supplier PRIMARY KEY (SupplierID),
	CONSTRAINT uniq_supplier UNIQUE (Supplier_name, Address, Phone_num)
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Supplies (
	SupplierID	INTEGER NOT NULL,
	IngredientID	INTEGER NOT NULL,
	CONSTRAINT pk_supplies PRIMARY KEY (SupplierID, IngredientID),
	CONSTRAINT fk_supplies_supp FOREIGN KEY (SupplierID)
				      	REFERENCES Supplier(SupplierID)
				      	ON DELETE CASCADE
				     	ON UPDATE CASCADE,
	CONSTRAINT fk_supplies_ingr FOREIGN KEY (IngredientID)
				      	REFERENCES Ingredients(IngredientID)
				      	ON DELETE CASCADE
				     	ON UPDATE CASCADE
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Coffeeshop (
	ShopID		SERIAL NOT NULL,
	Shop_address	VARCHAR(30) NOT NULL,
	Phone_num	VARCHAR(30) NOT NULL,
	CONSTRAINT pk_coffeeshop PRIMARY KEY (ShopID),
	CONSTRAINT uniq_coffeeshop UNIQUE (Shop_address, Phone_num)
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Hires (
	ShopID		INTEGER NOT NULL,
	EmployeeID	INTEGER NOT NULL,
	Hire_date	DATE NOT NULL,
	Leave_date	DATE,
	CONSTRAINT pk_hires PRIMARY KEY (ShopID, EmployeeID),
	CONSTRAINT fk_hires_shop FOREIGN KEY (ShopID)
					REFERENCES CoffeeShop(ShopID)
					ON DELETE CASCADE
					ON UPDATE CASCADE,
	CONSTRAINT fk_hires_emp FOREIGN KEY (EmployeeID)
					REFERENCES Employee(EmployeeID)
					ON DELETE CASCADE
					ON UPDATE CASCADE
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Inventory (
	ItemID		SERIAL NOT NULL,
	EmployeeID	INTEGER NOT NULL,
	Unaccounted_for	VARCHAR(10) NOT NULL,
	Period		month NOT NULL,
	Date_checked	DATE NOT NULL,
	CONSTRAINT pk_inventory PRIMARY KEY (ItemID),
	CONSTRAINT fk_inventory_emp FOREIGN KEY (EmployeeID)
				      	REFERENCES Employee(EmployeeID)
				      	ON DELETE CASCADE
				     	ON UPDATE CASCADE
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Documents (
	IngredientID	INTEGER NOT NULL,
	ItemID		INTEGER NOT NULL,
	Purchase_amount	VARCHAR(10) NOT NULL,
	Sale_amount	VARCHAR(10) NOT NULL,
	Left_over	VARCHAR(10) NOT NULL,
	CONSTRAINT pk_documents PRIMARY KEY (IngredientID, ItemID),
	CONSTRAINT fk_documents_ingr FOREIGN KEY (IngredientID)
						REFERENCES Ingredients(IngredientID)
						ON DELETE CASCADE
						ON UPDATE CASCADE,
	CONSTRAINT fk_documents_item FOREIGN KEY (ItemID)
						REFERENCES Inventory(ItemID)
						ON DELETE CASCADE
						ON UPDATE CASCADE
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Customer (
	CustomerID		SERIAL NOT NULL,
	Phone_num		VARCHAR(12),
	Customer_name		VARCHAR(30),
	Membership_status	BOOLEAN NOT NULL,
	CONSTRAINT pk_customer PRIMARY KEY (CustomerID)
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Menu_item (
	ProductID	SERIAL NOT NULL,
	Product_name	VARCHAR(20) NOT NULL,
	Size		size_type NOT NULL,
	Store_price	NUMERIC (6, 2),
	CONSTRAINT pk_menu_item PRIMARY KEY (ProductID)
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS SOrder (
	SOrderID	SERIAL NOT NULL,
	CustomerID	INTEGER NOT NULL,
	Discount	NUMERIC (5, 2),
	Is_online	BOOLEAN NOT NULL,
	CONSTRAINT pk_sorder PRIMARY KEY (SOrderID),
	CONSTRAINT chk_sorder CHECK (Discount >= 0 AND Discount <= 1),
	CONSTRAINT fk_sorder_cust FOREIGN KEY (CustomerID)
					REFERENCES Customer(CustomerID)
					ON DELETE CASCADE
					ON UPDATE CASCADE
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Payed_to (
	SOrderID	INTEGER NOT NULL,
	EmployeeID	INTEGER NOT NULL,
	Pay_info payment_type NOT NULL,
	Pay_date 	TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	CONSTRAINT pk_payed_to PRIMARY KEY (SOrderID, EmployeeID),
	CONSTRAINT fk_payed_to_sord FOREIGN KEY (SOrderID)
					REFERENCES SOrder(SOrderID)
					ON DELETE CASCADE
					ON UPDATE CASCADE,
	CONSTRAINT fk_payed_to_empl FOREIGN KEY (EmployeeID)
					REFERENCES Employee(EmployeeID)
					ON DELETE CASCADE
					ON UPDATE CASCADE
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Fills (
	SOrderID	INTEGER,
	EmployeeID	INTEGER,
	Time_received 	TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	Time_delivered 	TIMESTAMP NOT NULL,
	CONSTRAINT pk_fils PRIMARY KEY (SOrderID, EmployeeID),
	CONSTRAINT fk_fills_sord FOREIGN KEY (SOrderID)
					REFERENCES SOrder(SOrderID)
					ON DELETE CASCADE
					ON UPDATE CASCADE,
	CONSTRAINT fk_fills_empl FOREIGN KEY (EmployeeID)
					REFERENCES Employee(EmployeeID)
					ON DELETE CASCADE
					ON UPDATE CASCADE,
	CONSTRAINT chk_fills_rec 
		CHECK (Time_received > '2015-10-22 00:00:00-00'),
	CONSTRAINT chk_fills_del CHECK (Time_received < Time_delivered)
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Uses (
	IngredientID	INTEGER NOT NULL,
	ProductID	INTEGER NOT NULL,
	Amount_used	VARCHAR(10) NOT NULL,
	CONSTRAINT pk_uses PRIMARY KEY (IngredientID, ProductID),
	CONSTRAINT fk_uses_ingr FOREIGN KEY (IngredientID)
					REFERENCES Ingredients(IngredientID)
					ON DELETE CASCADE
					ON UPDATE CASCADE,
	CONSTRAINT fk_uses_prod FOREIGN KEY (ProductID)
					REFERENCES Menu_item(ProductID)
					ON DELETE CASCADE
					ON UPDATE CASCADE
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Has (
	ProductID	INTEGER NOT NULL,
	SOrderID	INTEGER NOT NULL,
	Product_price	NUMERIC (4, 2) NOT NULL,
	Product_quantity	INTEGER NOT NULL,
	Special_instr	VARCHAR(50),
	CONSTRAINT pk_has PRIMARY KEY (ProductID, SOrderID),
	CONSTRAINT fk_has_prod FOREIGN KEY (ProductID)
					REFERENCES Menu_item(ProductID)
					ON DELETE CASCADE
					ON UPDATE CASCADE,
	CONSTRAINT fk_has_sord FOREIGN KEY (SOrderID)
					REFERENCES SOrder(SOrderID)
					ON DELETE CASCADE
					ON UPDATE CASCADE,
	CONSTRAINT chk_has_price CHECK (Product_price > 0),
	CONSTRAINT chk_has_qty CHECK (Product_quantity > 0)
	) TABLESPACE CSdata ;

CREATE TABLE IF NOT EXISTS Payment (
	POrderID	INTEGER NOT NULL,
	Payed_for	BOOLEAN NOT NULL,
	CONSTRAINT pk_payment PRIMARY KEY (POrderID),
	CONSTRAINT fk_payment_pord FOREIGN KEY (POrderID)
					REFERENCES POrder(POrderID)
					ON DELETE CASCADE
					ON UPDATE CASCADE
	) TABLESPACE CSdata ;
