Report for Database Design Project (In beauty salon)
Part 1: Problem Modeling
Part 1: Problem Description and Modeling
1.1 Background and Motivation
With the rapid growth of service-oriented businesses, traditional manual or fragmented appointment management methods are no longer sufficient.
In a typical beauty salon, operations involve multiple roles—customers, hairdressers, and administrators—each interacting with the system in different ways.
Without a centralized database system, problems such as inconsistent records, redundant data, and lack of access control frequently arise.
From a database design perspective, this scenario naturally introduces several challenges:
•	Managing multiple user roles with different responsibilities
•	Maintaining data consistency across reservations, services, and payments
•	Supporting frequent transactional operations with reliability and security
Therefore, a database-driven management system is required to integrate appointment scheduling, service management, payment processing, and performance evaluation into a unified platform.
1.2 Problem Modeling
The system is modeled around three primary actors:
•	Customers, who browse services, make reservations, complete payments, and provide ratings
•	Hairdressers, who manage their schedules, perform services, and receive performance evaluations
•	Administrators, who oversee system data, manage services and staff, and ensure operational correctness
Among all entities, reservations serve as the core transactional object.
Each reservation connects customers, hairdressers, services, and payments, making it the central unit for both operational processing and analytical queries.
This reservation-centered modeling approach ensures:
•	Clear business workflow representation
•	Minimal redundancy across related entities
•	Natural extensibility for future features such as statistics or reporting


Part 2 : Conceptual Design
Hints: (The report will not show the ER Diagram, the graph is submitted individually according to requirement.)
2.1 Initial ER Modeling
Based on the problem analysis, an initial Entity–Relationship (ER) model was constructed to reflect real-world interactions within the salon.
Core entity sets include:
•	Customer
•	Hairdresser
•	Service
•	Reservation
•	Payment
Each entity corresponds to a real-world object, while relationships capture business constraints such as booking, offering services, and completing payments.
2.2 ER Diagram Iteration and Refinement
During the design process, several iterations were performed to improve clarity and correctness:
•	Removal of Product-related Entities
Product inventory was initially considered. However, since inventory management is not part of the project requirements, these entities were removed to keep the schema focused.
•	Simplification of Performance Modeling
Instead of a standalone performance entity, performance metrics (e.g., rating) were derived from completed reservations, reducing redundancy.
•	Separation of Work Time Scheduling
Hairdresser work schedules were abstracted into an independent table.
This design choice improves normalization and allows flexible schedule management without affecting reservation data.
These iterations demonstrate a transition from a purely conceptual model toward a database-oriented abstraction aligned with implementation constraints.

Part 3: Logical and Physical Design:
3.1 Relational Schema Mapping
The refined ER model was transformed into a relational schema using standard mapping rules:
•	Each entity set becomes a table
•	Primary keys uniquely identify tuples
•	Foreign keys enforce relationships between tables
All relations are designed to satisfy Third Normal Form (3NF), ensuring that:
•	Each non-key attribute depends only on the primary key
•	Update, insertion, and deletion anomalies are avoided
Foreign key constraints are used to maintain referential integrity, particularly between reservations and their associated customers, services, and hairdressers.
3.2 User Privileges and Access Control
To enhance system security, the database adopts a role-based privilege design.
Three application-level users are defined:
•	customer_app: limited to browsing services, creating reservations, and making payments
•	hairdresser_app: allowed to view assigned reservations and schedules
•	admin_app: responsible for administrative operations such as managing services and staff
This separation enforces the principle of least privilege, ensuring that each role can access only the data necessary for its functionality.

Part 4: Features demonstration and requirement checking:
The implemented system satisfies all required backend features:
1.	Data Integrity Mechanisms
Triggers ensure consistency across reservations, payments, and ratings.
2.	BLOB
3.	Security and Access Control
Privilege separation is implemented using multiple database users.
4.	Performance Observation
Query execution time is displayed in administrative views to demonstrate system responsiveness.
5.	Administrative Operations
Administrators can manage reservations, services, and hairdresser information through controlled interfaces.

1)	Trigger
In this project, we design some triggers in table.
 
2)	BLOB
BLOB we use for storing pictures first, we find it advantage and disadvantage as we show in PPT.
 
3)	Safe connection
If account wants to get the authority he doesn’t have it, mysql will deny his request.
 
4)	Checking query time in webpages.
To meet the requirement, we set a webpage to show the query time in administrator management page which uses for checking reservation.
  
5)	Admin management.
We give a lot management function for business admin to manage data. 

Conclusion
This project demonstrates a complete database design workflow—from problem modeling and conceptual abstraction to logical implementation and frontend integration.
Through iterative ER refinement, normalization, and role-based access control, the system achieves both functional correctness and robust data management.


Members working:
金正阳 2430026085 : PHP interface, PPT making, report
张灏帅 2430026265: Generate data, BLOB, Database construct
李秉轩 2430026091: Test all function
刘嘉翔 2430026128: Static webpages design
张懿 2430026281: Static webpages design
罗杨峰 2430026151: Static webpages design

