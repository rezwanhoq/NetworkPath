# Network Path

### Usage:

	php Main.php


### Sample output:
	Enter Device From, Device To and Latency (eg: A B 10 followed by ENTER key): A B 10

	OUTPUT: A=>B=>10**
  
	Do you want to continue? (Yes/Quit): YES  
	Enter Device From, Device To and Latency (eg: A B 10 followed by ENTER key): E A 80  

	OUTPUT: E=>D=>C=>A=>60
  
	Do you want to continue? (Yes/Quit): YES
	Enter Device From, Device To and Latency (eg: A B 10 followed by ENTER key): A E 10

	OUTPUT: Path Not Found
	
	Do you want to continue? (Yes/Quit): QUIT

	OUTPUT: Have a nice day!

#### Sample output for invalid data:
	Enter Device From, Device To and Latency (eg: A B 10 followed by ENTER key): ADSLKFJ
	OUTPUT: Invaild data
	
