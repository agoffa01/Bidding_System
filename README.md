Bidding-System
==============
Senior design project.

Technoloiges Used: PHP, Apache Web Server, PostgreSQL, HTML, CSS, Boostrap Framework, JavaScript.

Functional Descriptive Detailed Requirements:

- Potential users must register on the website 
	- Users must use their NYU email to register and the system will confirm they are NYU students.
	- Email is sent to the user for confirmation.
	- User is directed to create a username and password.
	- User is a buyer by default and has the option to sell items.

- The user must be logged in
	- Username should be at least 5 characters excluding special characters (Only ASCII characters).
	- Password should be at least 8 characters including at least one number, one letter and one special character.
	- User is taken to their portal where the latest auctions are taking place at the moment.

- User can search and bid on items
	- If the user wants to buy a product, they will click on it and are taken to the product’s 
      bidding page.
	- User has to have the highest bid in order to purchase the product.

- User can sell items
	- If the user wants to sell an item, the user clicks the selling tab in the main page and  is taken to their inventory page where they can add, edit and delete inventory.
	- Once a bid has started, the seller is not allowed to change anything about it.

- Order ticket and confirmation is generated
	- Once the buyer seals the bid, their information is obtained and placed in the order 
       ticket such as their name, email address, and phone number.
	- A location for the meet up between the seller and buyer is decided by both parties  
       through personal communication.

- Possible meet up locations are given 
	-A map of the NYU campus and Poly campus are given to choose a meet up location.
	- An option of specifying a personal meet up is also given by the seller for the buyer to  
      agree.

- Seller notifies winner 
	-  An email is sent to the seller about the winner.
	- The system will notify the seller who won the bid.
	- The seller will notify the buyer that they won the bid via email.

- Seller can close items 
	- The seller is allowed to close an item before the bidding and after the bidding ends.
	- The seller is not allowed to close an item during the bidding process.
	- The item is closed by default when the deadline for the bid is reached.
