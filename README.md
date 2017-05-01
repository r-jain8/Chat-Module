# Chat-Module
 A real time chat feature for a social e-commerce platform that involves exchange of user text messages, exchange of product thumbnail images by product code annotation and a selection cart to assist online buying. 
 
 ## Steps to be followed -
  1. Download all the php files and store them in a folder named "chatbox" and save it in "htdocs" folder of Xampp.
  2. Install aws.phar file by following the steps given [here](https://docs.aws.amazon.com/aws-sdk-php/v3/guide/getting-started/installation.html) and save it in your "chatbox" folder.
  3. Create an AWS account and edit your credentials, i.e. the AWS_KEY and AWS_SECRET_KEY in the "core_users.php" file.
  4. Download jquery-3.1.1 and store it in "chatbox" folder.
  5. Download the security certificate "cacert.pem" and rename it to "curl-ca-bundle". Save it in "chatbox" folder.
 
  6. Create four tables in DynamoDB as follows - 
   * Table 1 : Users (UserName (S),	UserPassword (S),	FriendList (NS),	OnlineStatus (N),	UserID (S)).
   * Table 2 : Products(ProductCode (S),	ProcductID (N),	ProductLink (S),	ProductName (S),	ProductThumbnail (S)).
   * Table 3 : Selection Cart(UsersId (S),	DateAndTime (S),	ProductCode (S),	ProductLink (S),	ProductName (S),	ProductThumbnail (S)).
   * Table 4 : Chats(UsersId (S),	DateAndTime (S),	Message (S),	Sender (S)).
 
   7. Select the products you want to share in your chats from Amazon, Flipcart etc..
   8. Add their link to the "Products" table under "ProductLink".
   9. Store the product thumbnail images in a S3 bucket and add their S3 links generated to the "Products" table under "ProductThumbnail" attribute.
 
 * Example of an entry in Users Table : 
   Angelina Jolie,	angelinia824,	{ 12, 8, 7, 5, 4, 3, 19, 20, 2, 10, 18, 1 }, 	0,	14
 * Example of an entry in Products Table : 
   motoG12,	1,	[Amazon link](http://www.amazon.in/Moto-Plus-4th-Gen-Black/dp/B01DDP7GZK/ref=br_asw_pdt-6?pf_rd_m=A1VBAL9TL5WCBF&pf_rd_s=&pf_rd_r=18Q1RB591PK2311Q6927&pf_rd_t=36701&pf_rd_p=4b2cd40d-25b0-4386-b578-3e7fbfdbc77d&pf_rd_i=desktop),	Moto G Plus Smartphone,	"image link from S3"
 
    *Now you're good to go!*
 1.  Open Xampp and start Apache and MySQL
 2.  Open Shell in Xampp and run the command "php -q c:/xampp/htdocs/chatbox/server.php".
 3.  Finally, open "localhost/chatbox/login_users.php" in browser and you're good to go!
