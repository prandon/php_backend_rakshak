
<?php
	class DbOperations{
		private $con;

		function __construct(){
			require_once dirname(__FILE__).'/DbConnect.php';

			$db = new DbConnect();

			$this->con = $db->connect();

		}


//login part
		public function userLogin($username,$pass){
			$stmt = $this->con->prepare("SELECT id FROM login WHERE username=? AND password=?");
			$stmt->bind_param("ss",$username,$pass);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows>0;
		}

		public function getAccountType($username){
			$stmt= $this->con->prepare("SELECT type FROM login WHERE username=?");
			$stmt->bind_param("s",$username);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}	

//admin functionalities
			//add new employee
		public function addNewEmployee($name, $email, $dob, $phone, $address){
			//check if already exists
			if($this->isEmployeeExist($email,$phone)){
				//aleady exist
				return 0;
			}
			else{
				$stmt = $this->con->prepare("INSERT INTO `employees` (`name`, `email`, `dob`, `phone`, `address`) VALUES (?, ?, ?, ?, ?)");
				$stmt->bind_param("sssss",$name, $email, $dob, $phone, $address);
			
				//insert into login table also
				$stmt1 = $this->con->prepare("INSERT INTO `login` (`id`, `username`, `password`, `type`) VALUES (NULL, ?, ?, 'e')");
			
				if($stmt->execute()){
					$res = $this->getEmployeeId($email);
					$empid = "EMP".$res['id'];
					$stmt1->bind_param("ss",$empid,$phone);
					if ($stmt1->execute()) {
						
						//successful
						return 1;
					}
					else{
						return 2;
					}
				}
				else{
					//failed
					return 2;
				}
			}			
		}

			//get employee id
		public function getEmployeeId($email){
			$stmt = $this->con->prepare("SELECT id FROM employees WHERE email = ?");
			$stmt->bind_param("s", $email);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}

			//remove employee
		public function removeEmployee($id){
			$stmt = $this->con->prepare("DELETE from employees WHERE id = ?");
			$stmt->bind_param("s",$id);

			$stmt1 = $this->con->prepare("DELETE FROM login WHERE username = ?");
			$empid = "EMP".$id;
			$stmt1->bind_param("s",$empid);
			if($stmt->execute() and $stmt1->execute()){
				//sucessful
				return 1;
			}
			else{
				//failed
				return 2;
			}
		}

			//get employee info
		public function getEmployeeInfo($id){
			$stmt = $this->con->prepare("SELECT name FROM employees WHERE id = ?");
			$stmt->bind_param("s", $id);
			$stmt->execute();
			$empinfo = $stmt->get_result()->fetch_assoc();
			return $empinfo;
		}


			//add new customer
		public function addNewCustomer($name, $email, $phone, $address){
			//check if already exists
			if($this->isCustomerExist($email,$phone)){
				//aleady exist
				return 0;
			}
			else{
				$stmt = $this->con->prepare("INSERT INTO `customer` (`name`, `email`, `phone`, `address`) VALUES (?, ?, ?, ?)");
				$stmt->bind_param("ssss",$name, $email, $phone, $address);
			
				//insert into login table also
				$stmt1 = $this->con->prepare("INSERT INTO `login` (`id`, `username`, `password`, `type`) VALUES (NULL, ?, ?, 'c')");
			
				if($stmt->execute()){
					$res = $this->getCustomerId($email);
					$custID = "CUST".$res['id'];
					$stmt1->bind_param("ss",$custID,$phone);
					$stmt1->execute();
					//successful
					return 1;
				}
				else{
					//failed
					return 2;
				}
			}
		}
			//remove customer
		public function removeCustomer($id){
			$stmt = $this->con->prepare("DELETE from customer WHERE id = ?");
			$stmt->bind_param("s",$id);

			$stmt1 = $this->con->prepare("DELETE FROM login WHERE username = ?");
			$custID = "CUST".$id;
			$stmt1->bind_param("s",$custID);
			if($stmt->execute()){

				//sucessful
				$stmt1->execute();
				return 1;
			}
			else{
				//failed
				return 2;
			}
		}

			//get customer info
		public function getCustomerInfo($id){
			$stmt = $this->con->prepare("SELECT name FROM customer WHERE id = ?");
			$stmt->bind_param("s", $id);
			$stmt->execute();
			$custinfo = $stmt->get_result()->fetch_assoc();
			return $custinfo;	
		}



			//get employee id
		public function getCustomerId($email){
			$stmt = $this->con->prepare("SELECT id FROM customer WHERE email = ?");
			$stmt->bind_param("s", $email);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}

			//admin add new schedule
		public function addNewSchedule($s_date, $s_time, $c_time, $siteid, $checkpointid, $empid){
			$stmt = $this->con->prepare("INSERT INTO schedules VALUES (?,?,?,?,?,?,0)");
			$stmt->bind_param("ssssss",$s_date,$s_time,$c_time, $siteid,$checkpointid,$empid);
			if($stmt->execute()){
				//successful
				return 1;
			}
			else{
				//failed
				return 2;
			}
		}

		//get site id
		public function getSiteId($sitename){
			$stmt = $this->con->prepare("SELECT siteid FROM sites WHERE sitename = ?");
			$stmt->bind_param("s", $sitename);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}

		//admin add new site
		public function addNewSite($sitename,$siteaddress,$custid){
			$stmt = $this->con->prepare("INSERT INTO sites VALUES (NULL,?,?,?,0)");
			$stmt->bind_param("sss",$sitename,$siteaddress,$custid);

			//create checkpoints table for site
			//$stmt1 = $this->con->prepare("CREATE TABLE ? ( `checkpointid` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(50) NOT NULL , PRIMARY KEY (`checkpointid`)) ENGINE = InnoDB;");
			
			$id = $this->getSiteId($sitename);
			$siteid = $id["siteid"];

			if($stmt->execute()){
				//$id = $this->getSiteId($sitename);
				//$siteid = $id["siteid"];
				//$tablename = "checkpoint_".$siteid;
				//$stmt1->bind_param("s",$siteid);
				//$stmt1->execute();
				//successful
				return 1;
			}
			else{
				//failed
				return 2;
			}	
		}

		//remove site
		public function removeSite($siteid){
			$stmt = $this->con->prepare("DELETE FROM sites WHERE siteid = ?");
			$stmt->bind_param("s",$siteid);
			if($stmt->execute()){
				//successful
				return 1;
			}
			else{
				//failure
				return 2;
			}

		}

		//get site info
		public function getSiteInfo($siteid){
			$stmt = $this->con->prepare("SELECT sitename FROM sites WHERE siteid = ?");
			$stmt->bind_param("s",$siteid);
			$stmt->execute();
			$siteInfo = $stmt->get_result()->fetch_assoc();
			return $siteInfo;
		}

		//create checkpoints table
		/*public function createCheckpointTable($siteid, $sitename){
			$stmt = $this->con->prepare("CREATE TABLE ? ( `checkpointid` INT NOT NULL AUTO_INCREMENT , `name` VARCHAR(50) NOT NULL , PRIMARY KEY (`checkpointid`)) ENGINE = InnoDB;")

			$tablename = "checkpoint_".$sitename;
			$stmt->bind_param("s",$tablename);
			if($stmt->execute()){
				return true;
			}
			else{
				return false;
			}
		}*/

		//admin add new checkpoint
		public function addNewCheckpoint($siteid,$c_name){
			//$stmt = $this->con->prepare("INSERT INTO checkpoints VALUES ()");
		}

		public function getAllSchedules(){
			$sth = mysqli_query($this->con, "SELECT * FROM schedules");

			$rows = array();
			while($r = mysqli_fetch_assoc($sth)) {
				//get sitename from siteid
				$sitename = $this->getSiteName($r['siteid']);
				$r['sitename'] = $sitename['sitename'];

				//get checkpoint name from chekpoint id
				$checkpointname = $this->getCheckpointName($r['checkpointid']);
				$r['checkpointname'] = $checkpointname['checkpointname'];

				//get employee name from employeeid
				$empname = $this->getEmployeeName($r['empid']);
				$r['empname'] = $empname['name'];

    			$rows[] = $r;

			}
			return $rows;
		}

		//get sitename from siteid
		public function getSiteName($siteid){
			$stmt = $this->con->prepare("SELECT sitename FROM sites WHERE siteid = ?");
			$stmt->bind_param("s", $siteid);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}
		//get employeename from empid
		public function getEmployeeName($empid){
			$stmt = $this->con->prepare("SELECT name FROM employees WHERE id = ?");
			$stmt->bind_param("s", $empid);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}
		//get checkpointname from checkpoint id
		public function getCheckpointName($checkpointid){
			$stmt = $this->con->prepare("SELECT checkpointname FROM checkpoints_table WHERE checkpointid = ?");
			$stmt->bind_param("s", $checkpointid);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}


		
		//get all sites
		public function getAllSites(){
			$stmt = $this->con->prepare("SELECT sitename FROM sites");
			$stmt->execute();
        	$result = $stmt->get_result();
        	return $result;
		}

		//get all employees
		public function getAllEmployees(){
			$stmt = $this->con->prepare("SELECT name FROM employees");
			$stmt->execute();
        	$result = $stmt->get_result();
        	return $result;
		}

		//get all sites all info
		public function getAllSitesInfo(){
			$query = "SELECT site.siteid, site.sitename, site.siteaddress, site.custid, site.checkpoints, site_image.url from sites site, site_images site_image where site.siteid = site_image.siteid";
			$sth = mysqli_query($this->con, $query);

			$rows = array();
			while($r = mysqli_fetch_assoc($sth)) {
    			$rows[] = $r;
			}
			return $rows;
		}

		//get all emp info
		public function getAllEmpInfo(){
			$sth = mysqli_query($this->con, "SELECT emp.id, emp.name, emp.email, emp.dob, emp.phone, emp.address, emp_image.url from employees emp, employee_images emp_image where emp.id=emp_image.id");

			$rows = array();
			while($r = mysqli_fetch_assoc($sth)) {
    			$rows[] = $r;
			}
			return $rows;
		}

		//get all cust info
		public function getAllCustInfo(){
			$sth = mysqli_query($this->con, "SELECT * FROM customer");

			$rows = array();
			while($r = mysqli_fetch_assoc($sth)) {
    			$rows[] = $r;
			}
			return $rows;
		}

		public function getTotalCheckpoints($id){
			$stmt = $this->con->prepare("SELECT checkpoints FROM sites WHERE siteid = ?");
			$stmt->bind_param("s",$id);
			$stmt->execute();
			$result = $stmt->get_result()->fetch_assoc();
			return $result;
		}

		public function updateCheckpointCount($siteid, $value){
			$stmt = $this->con->prepare("UPDATE sites SET checkpoints = ? WHERE siteid = ?");
			$stmt->bind_param("ss",$value, $siteid);
			$stmt->execute();
		}

		//add new checkpoint 
		public function addCheckpoint($siteid, $name){
			$result = $this->getTotalCheckpoints($siteid);
			$ckpts = $result['checkpoints'];
			$newckpts = $ckpts+1;
			$undsk = "_";
			$chkptid = $siteid.$undsk;
			$chkptid = $chkptid.$newckpts;

			$stmt = $this->con->prepare("INSERT INTO checkpoints_table VALUES (?,?,?)");
			$stmt->bind_param("sss",$siteid,$chkptid,$name);
			if($stmt->execute()){
				//successful
				//update count
				$this->updateCheckpointCount($siteid, $newckpts);
				return 1;
			}
			else{
				//failed
				return 2;
			}
		}

		//get checkpoint name from siteid
		public function getCheckpoints($siteid){
			$stmt = $this->con->prepare("SELECT checkpointname FROM checkpoints_table WHERE siteid = ?");
			$stmt ->bind_param("s",$siteid);
			$stmt->execute();
        	$result = $stmt->get_result();
        	return $result;
		}

		//get empid by email
		public function getEmpIdByEmail($email){
			$stmt = $this->con->prepare("SELECT id FROM employees WHERE email = ?");
			$stmt->bind_param("s", $email);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}

		//get empid from emp name
		public function getEmpId($name){
			$stmt = $this->con->prepare("SELECT id FROM employees WHERE name = ?");
			$stmt->bind_param("s", $name);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}

		//get checkpoint id from checkpt id
		public function getCheckpointId($chekptname){
			$stmt = $this->con->prepare("SELECT checkpointid FROM checkpoints_table WHERE checkpointname = ?");
			$stmt->bind_param("s", $chekptname);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}

		//admin get all checkpoints by siteid
		public function getAllCheckpointsBySite($siteid){
			$sth = mysqli_query($this->con, "SELECT * FROM checkpoints_table WHERE siteid = $siteid");

			$rows = array();
			while($r = mysqli_fetch_assoc($sth)) {
    			$rows[] = $r;
			}
			return $rows;
		}

		//admin add new schedules bulk
		public function adminAddSchedulesBulk($query){
			if ($this->con->query($query) === TRUE) {
    			return 1;
			} else {
    			return 2;
			}
		}

//customer functionalities
		//get all sites
		public function getAllCustomerSites($custid){
			
			$sth = mysqli_query($this->con, "SELECT site.siteid, site.sitename, site.siteaddress, site.custid, site.checkpoints, site_image.url from sites site, site_images site_image where site.siteid = site_image.siteid and  custid = $custid");

			$rows = array();
			while($r = mysqli_fetch_assoc($sth)) {
    			$rows[] = $r;
			} 
			return $rows;
		
		}

		//get all customer schedules
		public function getAllCustomerSchedules($siteid){
			$query = "SELECT sc.s_date, sc.s_time, sc.c_time, sc.siteid, sc.checkpointid, sc.status, s.sitename, c.checkpointname FROM schedules sc, sites s, checkpoints_table c WHERE sc.siteid in (SELECT siteid FROM sites WHERE custid = ?) AND s.siteid=sc.siteid AND c.checkpointid=sc.checkpointid";
			//$stmt = $this->con->prepare($query);
			//$stmt->bind_param("s",$custid);
			//$stmt->execute();
			//return $stmt->get_result()->fetch_array();
			//SELECT sc.s_date, sc.s_time, sc.c_time, sc.siteid, sc.checkpointid, sc.status, s.sitename, c.checkpointname FROM schedules sc, sites s, checkpoints_table c WHERE sc.siteid in (SELECT siteid FROM sites WHERE custid = ".$custid.") AND s.siteid=sc.siteid AND c.checkpointid=sc.checkpointid

			$sth = mysqli_query($this->con, "SELECT sc.s_date, sc.s_time, sc.c_time, sc.siteid, sc.checkpointid, sc.status, s.sitename, c.checkpointname FROM schedules sc, sites s, checkpoints_table c WHERE sc.siteid = ".$siteid." AND s.siteid=sc.siteid AND c.checkpointid=sc.checkpointid");

			$rows = array();
			while($r = mysqli_fetch_assoc($sth)) {
				
    			$rows[] = $r;

			}
			return $rows;
		}



//employee funtionalities
		public function employeeGetSchedules($empid){
			//$stmt = $this->con->prepare("SELECT * FROM schedules WHERE empid = ?");
			//$stmt->bind_param("s",$empid);

			//$res = $stmt->execute();

			$sth = mysqli_query($this->con, "SELECT * FROM schedules WHERE empid = ".$empid);

			$rows = array();
			while($r = mysqli_fetch_assoc($sth)) {
				//get sitename from siteid
				$sitename = $this->getSiteName($r['siteid']);
				$r['sitename'] = $sitename['sitename'];

				//get checkpoint name from chekpoint id
				$checkpointname = $this->getCheckpointName($r['checkpointid']);
				$r['checkpointname'] = $checkpointname['checkpointname'];

				//get employee name from employeeid
				$empname = $this->getEmployeeName($r['empid']);
				$r['empname'] = $empname['name'];

    			$rows[] = $r;

			}
			return $rows;
		}


		//check if user exists
		//check if employee exists
		public function isEmployeeExist($email,$phone){
			$stmt = $this->con->prepare("SELECT name FROM employees WHERE phone=? OR email=?");
			$stmt->bind_param("ss",$phone,$email);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows>0;
		}

		//check if customer exists
		public function isCustomerExist($email,$phone){
			$stmt = $this->con->prepare("SELECT name FROM customer WHERE phone=? OR email=?");
			$stmt->bind_param("ss",$phone,$email);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows>0;
		}

		//get user info temprary function
		public function getUserInfo($name){
			//$stmt = $this->con->prepare("SELECT name, email FROM employees");
			//$stmt->bind_param("s", $name);
			//$stmt->execute();
			$sth = mysqli_query($this->con, "SELECT name, email FROM employees");
			$rows = array();
			while($r = mysqli_fetch_assoc($sth)) {
    			$rows[] = $r;
			}
			return $rows;
		}
		


}