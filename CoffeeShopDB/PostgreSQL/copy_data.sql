COPY Department(DepartmentID,Department_name)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Department.csv'
DELIMITER ',' CSV HEADER;

COPY Employee(EmployeeID,Name,SSN,Address,Birth_date,Sex,ManagerID)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Employee.csv'
DELIMITER ',' CSV HEADER;

COPY Works_for(DepartmentID,EmployeeID,Dept_start,Dept_end,Wage,Salary)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Works_for.csv'
DELIMITER ',' CSV HEADER;

COPY POrder(POrderID,EmployeeID,Received)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/POrder.csv'
DELIMITER ',' CSV HEADER;

COPY Ingredients(IngredientID,POrderID,Price,Quantity)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Ingredients.csv'
DELIMITER ',' CSV HEADER;

COPY Supplier(SupplierID,Supplier_name,Address,Phone_num)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Supplier.csv'
DELIMITER ',' CSV HEADER;

COPY Supplies(SupplierID,IngredientID)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Supplies.csv'
DELIMITER ',' CSV HEADER;

COPY CoffeeShop(ShopID,Shop_address,Phone_num)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Coffee_shop.csv'
DELIMITER ',' CSV HEADER;

COPY Hires(ShopID,EmployeeID,Hire_date,Leave_date)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Hires.csv'
DELIMITER ',' CSV HEADER;

COPY Inventory(ItemID,EmployeeID,Unaccounted_for,Period,Date_checked)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Inventory.csv'
DELIMITER ',' CSV HEADER;

COPY Documents(IngredientID,ItemID,Purchase_amount,Sale_amount,Left_over)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Documents.csv'
DELIMITER ',' CSV HEADER;

COPY Customer(CustomerID,Phone_num,Customer_name,Membership_status)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Customer.csv'
DELIMITER ',' CSV HEADER;

COPY Menu_item(ProductID,Product_name,Size,Store_price)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Menu_item.csv'
DELIMITER ',' CSV HEADER;

COPY SOrder(SOrderID,CustomerID,Discount,Is_online)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/SOrder.csv'
DELIMITER ',' CSV HEADER;

COPY Payed_to(SOrderID,EmployeeID,Pay_info,Pay_date)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Payed_to.csv'
DELIMITER ',' CSV HEADER;

COPY Fills(SOrderID,EmployeeID,Time_received,Time_delivered)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Fills.csv'
DELIMITER ',' CSV HEADER;

COPY Uses(IngredientID,ProductID,Amount_used)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Uses.csv'
DELIMITER ',' CSV HEADER;

COPY Has(ProductID,SOrderID,Product_price,Product_quantity,Special_instr)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Has.csv'
DELIMITER ',' CSV HEADER;

COPY Payment(POrderID,Payed_for)
FROM '/var/lib/postgresql/CoffeeShopDB/csv_data/Payment.csv'
DELIMITER ',' CSV HEADER;

