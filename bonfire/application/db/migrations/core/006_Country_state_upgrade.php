<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Migration_Country_state_upgrade extends Migration {
	
	public function up() 
	{
		$prefix = $this->db->dbprefix;
		
		/*
			Take care of a few preliminaries before updating: 
			
			- Add a state_code column to the user table
TODO			- Map users.state_id to the correct state_code and update each user
			- Remove the state_id column from the users table
			- Remove the country_id column from the users table - doesn't seem to be updated anyway
			
			- Remove the states table
			- Remove the countries table
		*/

		// Add a state_code column to the user table
		$this->dbforge->add_column('users', array(
				'state_code'	=> array(
					'type'			=> 'CHAR',
					'constraint'	=> 4,
					'null'			=> TRUE,
					'default'		=> NULL
				)
			)
		);
		
		// Map users.state_id to the correct state_code and update each user
		$sql = "SELECT * FROM {$prefix}states";
		$old_states_query = $this->db->query($sql);
		// loop through the current states
		$old_states_array = array();
		foreach ($old_states_query->result_array() as $state_rec)
		{
			$old_states_array[$state_rec['id']] = $state_rec['abbrev'];
		}

		$sql = "SELECT * FROM {$prefix}users";
		$users_query = $this->db->query($sql);
		// loop through the current users
		foreach ($users_query->result_array() as $user_rec)
		{
			
			if (!empty($user_rec['state_id']))
			{
				$this->db->query("UPDATE {$prefix}users SET `state_code` = '{$old_states_array[$user_rec['state_id']]}' WHERE `id` = '{$user_rec['id']}';");
			}
		}

		// Remove the state_id column from the users table
		$this->dbforge->drop_column('users', 'state_id');
		
		// Remove the country_id column from the users table
		$this->dbforge->drop_column('users', 'country_id');

		// Remove the states table
		$this->dbforge->drop_table('states');
		
		// Remove the countries table
		$this->dbforge->drop_table('countries');
		
	}
	
	//--------------------------------------------------------------------
	
	public function down() 
	{
		$prefix = $this->db->dbprefix;
		
		/*
			oh no we have to rollback ??
			really??
			please no!
		
			ok then.
			- Reinstate the states table
			- Reinstate the countries table
			- Add the country_id column into the users table
			- Add the state_id column into the users table
TODO			- Map the state_code values to the state_id and update each user
			- Remove the state_code column from the users table
		*/

		// Reinstate the states table
		$this->dbforge->add_field("`id` int(11) NOT NULL AUTO_INCREMENT");
		$this->dbforge->add_field("`name` char(40) NOT NULL");
		$this->dbforge->add_field("`abbrev` char(2) NOT NULL");
		$this->dbforge->add_key('id', true);
		$this->dbforge->create_table('states');
		
		$this->db->query("INSERT INTO {$prefix}states VALUES(1, 'Alaska', 'AK')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(2, 'Alabama', 'AL')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(3, 'American Samoa', 'AS')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(4, 'Arizona', 'AZ')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(5, 'Arkansas', 'AR')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(6, 'California', 'CA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(7, 'Colorado', 'CO')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(8, 'Connecticut', 'CT')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(9, 'Delaware', 'DE')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(10, 'District of Columbia', 'DC')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(12, 'Florida', 'FL')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(13, 'Georgia', 'GA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(14, 'Guam', 'GU')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(15, 'Hawaii', 'HI')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(16, 'Idaho', 'ID')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(17, 'Illinois', 'IL')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(18, 'Indiana', 'IN')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(19, 'Iowa', 'IA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(20, 'Kansas', 'KS')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(21, 'Kentucky', 'KY')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(22, 'Louisiana', 'LA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(23, 'Maine', 'ME')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(24, 'Marshall Islands', 'MH')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(25, 'Maryland', 'MD')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(26, 'Massachusetts', 'MA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(27, 'Michigan', 'MI')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(28, 'Minnesota', 'MN')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(29, 'Mississippi', 'MS')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(30, 'Missouri', 'MO')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(31, 'Montana', 'MT')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(32, 'Nebraska', 'NE')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(33, 'Nevada', 'NV')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(34, 'New Hampshire', 'NH')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(35, 'New Jersey', 'NJ')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(36, 'New Mexico', 'NM')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(37, 'New York', 'NY')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(38, 'North Carolina', 'NC')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(39, 'North Dakota', 'ND')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(40, 'Northern Mariana Islands', 'MP')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(41, 'Ohio', 'OH')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(42, 'Oklahoma', 'OK')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(43, 'Oregon', 'OR')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(44, 'Palau', 'PW')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(45, 'Pennsylvania', 'PA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(46, 'Puerto Rico', 'PR')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(47, 'Rhode Island', 'RI')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(48, 'South Carolina', 'SC')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(49, 'South Dakota', 'SD')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(50, 'Tennessee', 'TN')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(51, 'Texas', 'TX')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(52, 'Utah', 'UT')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(53, 'Vermont', 'VT')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(54, 'Virgin Islands', 'VI')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(55, 'Virginia', 'VA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(56, 'Washington', 'WA')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(57, 'West Virginia', 'WV')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(58, 'Wisconsin', 'WI')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(59, 'Wyoming', 'WY')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(60, 'Armed Forces Africa', 'AE')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(62, 'Armed Forces Canada', 'AE')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(63, 'Armed Forces Europe', 'AE')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(64, 'Armed Forces Middle East', 'AE')");
		$this->db->query("INSERT INTO {$prefix}states VALUES(65, 'Armed Forces Pacific', 'AP')");

		
		// Add countries table for our users.
		// Source: http://27.org/isocountrylist/
		$this->dbforge->add_field("iso CHAR(2) DEFAULT 'US' NOT NULL");
		$this->dbforge->add_field("name VARCHAR(80) NOT NULL");
		$this->dbforge->add_field("printable_name VARCHAR(80) NOT NULL");
		$this->dbforge->add_field("iso3 CHAR(3)");
		$this->dbforge->add_field("numcode SMALLINT");
		$this->dbforge->add_key('iso', true);
		$this->dbforge->create_table('countries');

		// And... the countries themselves. (whew!)
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AF','AFGHANISTAN','Afghanistan','AFG','004');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AL','ALBANIA','Albania','ALB','008');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('DZ','ALGERIA','Algeria','DZA','012');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AS','AMERICAN SAMOA','American Samoa','ASM','016');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AD','ANDORRA','Andorra','AND','020');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AO','ANGOLA','Angola','AGO','024');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AI','ANGUILLA','Anguilla','AIA','660');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AQ','ANTARCTICA','Antarctica',NULL,NULL);");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AG','ANTIGUA AND BARBUDA','Antigua and Barbuda','ATG','028');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AR','ARGENTINA','Argentina','ARG','032');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AM','ARMENIA','Armenia','ARM','051');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AW','ARUBA','Aruba','ABW','533');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AU','AUSTRALIA','Australia','AUS','036');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AT','AUSTRIA','Austria','AUT','040');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AZ','AZERBAIJAN','Azerbaijan','AZE','031');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BS','BAHAMAS','Bahamas','BHS','044');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BH','BAHRAIN','Bahrain','BHR','048');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BD','BANGLADESH','Bangladesh','BGD','050');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BB','BARBADOS','Barbados','BRB','052');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BY','BELARUS','Belarus','BLR','112');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BE','BELGIUM','Belgium','BEL','056');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BZ','BELIZE','Belize','BLZ','084');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BJ','BENIN','Benin','BEN','204');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BM','BERMUDA','Bermuda','BMU','060');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BT','BHUTAN','Bhutan','BTN','064');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BO','BOLIVIA','Bolivia','BOL','068');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BA','BOSNIA AND HERZEGOVINA','Bosnia and Herzegovina','BIH','070');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BW','BOTSWANA','Botswana','BWA','072');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BV','BOUVET ISLAND','Bouvet Island',NULL,NULL);");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BR','BRAZIL','Brazil','BRA','076');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('IO','BRITISH INDIAN OCEAN TERRITORY','British Indian Ocean Territory',NULL,NULL);");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BN','BRUNEI DARUSSALAM','Brunei Darussalam','BRN','096');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BG','BULGARIA','Bulgaria','BGR','100');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BF','BURKINA FASO','Burkina Faso','BFA','854');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('BI','BURUNDI','Burundi','BDI','108');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('KH','CAMBODIA','Cambodia','KHM','116');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CM','CAMEROON','Cameroon','CMR','120');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CA','CANADA','Canada','CAN','124');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CV','CAPE VERDE','Cape Verde','CPV','132');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('KY','CAYMAN ISLANDS','Cayman Islands','CYM','136');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CF','CENTRAL AFRICAN REPUBLIC','Central African Republic','CAF','140');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TD','CHAD','Chad','TCD','148');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CL','CHILE','Chile','CHL','152');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CN','CHINA','China','CHN','156');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CX','CHRISTMAS ISLAND','Christmas Island',NULL,NULL);");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CC','COCOS (KEELING) ISLANDS','Cocos (Keeling) Islands',NULL,NULL);");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CO','COLOMBIA','Colombia','COL','170');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('KM','COMOROS','Comoros','COM','174');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CG','CONGO','Congo','COG','178');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CD','CONGO, THE DEMOCRATIC REPUBLIC OF THE','Congo, the Democratic Republic of the','COD','180');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CK','COOK ISLANDS','Cook Islands','COK','184');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CR','COSTA RICA','Costa Rica','CRI','188');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CI','COTE D\'IVOIRE','Cote D\'Ivoire','CIV','384');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('HR','CROATIA','Croatia','HRV','191');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CU','CUBA','Cuba','CUB','192');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CY','CYPRUS','Cyprus','CYP','196');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CZ','CZECH REPUBLIC','Czech Republic','CZE','203');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('DK','DENMARK','Denmark','DNK','208');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('DJ','DJIBOUTI','Djibouti','DJI','262');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('DM','DOMINICA','Dominica','DMA','212');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('DO','DOMINICAN REPUBLIC','Dominican Republic','DOM','214');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('EC','ECUADOR','Ecuador','ECU','218');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('EG','EGYPT','Egypt','EGY','818');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SV','EL SALVADOR','El Salvador','SLV','222');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GQ','EQUATORIAL GUINEA','Equatorial Guinea','GNQ','226');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('ER','ERITREA','Eritrea','ERI','232');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('EE','ESTONIA','Estonia','EST','233');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('ET','ETHIOPIA','Ethiopia','ETH','231');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('FK','FALKLAND ISLANDS (MALVINAS)','Falkland Islands (Malvinas)','FLK','238');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('FO','FAROE ISLANDS','Faroe Islands','FRO','234');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('FJ','FIJI','Fiji','FJI','242');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('FI','FINLAND','Finland','FIN','246');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('FR','FRANCE','France','FRA','250');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GF','FRENCH GUIANA','French Guiana','GUF','254');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PF','FRENCH POLYNESIA','French Polynesia','PYF','258');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TF','FRENCH SOUTHERN TERRITORIES','French Southern Territories',NULL,NULL);");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GA','GABON','Gabon','GAB','266');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GM','GAMBIA','Gambia','GMB','270');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GE','GEORGIA','Georgia','GEO','268');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('DE','GERMANY','Germany','DEU','276');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GH','GHANA','Ghana','GHA','288');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GI','GIBRALTAR','Gibraltar','GIB','292');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GR','GREECE','Greece','GRC','300');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GL','GREENLAND','Greenland','GRL','304');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GD','GRENADA','Grenada','GRD','308');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GP','GUADELOUPE','Guadeloupe','GLP','312');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GU','GUAM','Guam','GUM','316');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GT','GUATEMALA','Guatemala','GTM','320');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GN','GUINEA','Guinea','GIN','324');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GW','GUINEA-BISSAU','Guinea-Bissau','GNB','624');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GY','GUYANA','Guyana','GUY','328');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('HT','HAITI','Haiti','HTI','332');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('HM','HEARD ISLAND AND MCDONALD ISLANDS','Heard Island and Mcdonald Islands',NULL,NULL);");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('VA','HOLY SEE (VATICAN CITY STATE)','Holy See (Vatican City State)','VAT','336');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('HN','HONDURAS','Honduras','HND','340');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('HK','HONG KONG','Hong Kong','HKG','344');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('HU','HUNGARY','Hungary','HUN','348');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('IS','ICELAND','Iceland','ISL','352');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('IN','INDIA','India','IND','356');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('ID','INDONESIA','Indonesia','IDN','360');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('IR','IRAN, ISLAMIC REPUBLIC OF','Iran, Islamic Republic of','IRN','364');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('IQ','IRAQ','Iraq','IRQ','368');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('IE','IRELAND','Ireland','IRL','372');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('IL','ISRAEL','Israel','ISR','376');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('IT','ITALY','Italy','ITA','380');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('JM','JAMAICA','Jamaica','JAM','388');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('JP','JAPAN','Japan','JPN','392');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('JO','JORDAN','Jordan','JOR','400');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('KZ','KAZAKHSTAN','Kazakhstan','KAZ','398');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('KE','KENYA','Kenya','KEN','404');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('KI','KIRIBATI','Kiribati','KIR','296');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('KP','KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF','Korea, Democratic People\'s Republic of','PRK','408');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('KR','KOREA, REPUBLIC OF','Korea, Republic of','KOR','410');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('KW','KUWAIT','Kuwait','KWT','414');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('KG','KYRGYZSTAN','Kyrgyzstan','KGZ','417');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('LA','LAO PEOPLE\'S DEMOCRATIC REPUBLIC','Lao People\'s Democratic Republic','LAO','418');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('LV','LATVIA','Latvia','LVA','428');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('LB','LEBANON','Lebanon','LBN','422');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('LS','LESOTHO','Lesotho','LSO','426');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('LR','LIBERIA','Liberia','LBR','430');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('LY','LIBYAN ARAB JAMAHIRIYA','Libyan Arab Jamahiriya','LBY','434');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('LI','LIECHTENSTEIN','Liechtenstein','LIE','438');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('LT','LITHUANIA','Lithuania','LTU','440');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('LU','LUXEMBOURG','Luxembourg','LUX','442');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MO','MACAO','Macao','MAC','446');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MK','MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF','Macedonia, the Former Yugoslav Republic of','MKD','807');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MG','MADAGASCAR','Madagascar','MDG','450');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MW','MALAWI','Malawi','MWI','454');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MY','MALAYSIA','Malaysia','MYS','458');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MV','MALDIVES','Maldives','MDV','462');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('ML','MALI','Mali','MLI','466');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MT','MALTA','Malta','MLT','470');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MH','MARSHALL ISLANDS','Marshall Islands','MHL','584');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MQ','MARTINIQUE','Martinique','MTQ','474');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MR','MAURITANIA','Mauritania','MRT','478');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MU','MAURITIUS','Mauritius','MUS','480');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('YT','MAYOTTE','Mayotte',NULL,NULL);");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MX','MEXICO','Mexico','MEX','484');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('FM','MICRONESIA, FEDERATED STATES OF','Micronesia, Federated States of','FSM','583');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MD','MOLDOVA, REPUBLIC OF','Moldova, Republic of','MDA','498');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MC','MONACO','Monaco','MCO','492');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MN','MONGOLIA','Mongolia','MNG','496');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MS','MONTSERRAT','Montserrat','MSR','500');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MA','MOROCCO','Morocco','MAR','504');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MZ','MOZAMBIQUE','Mozambique','MOZ','508');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MM','MYANMAR','Myanmar','MMR','104');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('NA','NAMIBIA','Namibia','NAM','516');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('NR','NAURU','Nauru','NRU','520');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('NP','NEPAL','Nepal','NPL','524');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('NL','NETHERLANDS','Netherlands','NLD','528');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AN','NETHERLANDS ANTILLES','Netherlands Antilles','ANT','530');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('NC','NEW CALEDONIA','New Caledonia','NCL','540');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('NZ','NEW ZEALAND','New Zealand','NZL','554');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('NI','NICARAGUA','Nicaragua','NIC','558');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('NE','NIGER','Niger','NER','562');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('NG','NIGERIA','Nigeria','NGA','566');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('NU','NIUE','Niue','NIU','570');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('NF','NORFOLK ISLAND','Norfolk Island','NFK','574');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('MP','NORTHERN MARIANA ISLANDS','Northern Mariana Islands','MNP','580');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('NO','NORWAY','Norway','NOR','578');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('OM','OMAN','Oman','OMN','512');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PK','PAKISTAN','Pakistan','PAK','586');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PW','PALAU','Palau','PLW','585');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PS','PALESTINIAN TERRITORY, OCCUPIED','Palestinian Territory, Occupied',NULL,NULL);");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PA','PANAMA','Panama','PAN','591');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PG','PAPUA NEW GUINEA','Papua New Guinea','PNG','598');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PY','PARAGUAY','Paraguay','PRY','600');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PE','PERU','Peru','PER','604');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PH','PHILIPPINES','Philippines','PHL','608');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PN','PITCAIRN','Pitcairn','PCN','612');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PL','POLAND','Poland','POL','616');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PT','PORTUGAL','Portugal','PRT','620');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PR','PUERTO RICO','Puerto Rico','PRI','630');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('QA','QATAR','Qatar','QAT','634');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('RE','REUNION','Reunion','REU','638');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('RO','ROMANIA','Romania','ROM','642');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('RU','RUSSIAN FEDERATION','Russian Federation','RUS','643');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('RW','RWANDA','Rwanda','RWA','646');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SH','SAINT HELENA','Saint Helena','SHN','654');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('KN','SAINT KITTS AND NEVIS','Saint Kitts and Nevis','KNA','659');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('LC','SAINT LUCIA','Saint Lucia','LCA','662');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('PM','SAINT PIERRE AND MIQUELON','Saint Pierre and Miquelon','SPM','666');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('VC','SAINT VINCENT AND THE GRENADINES','Saint Vincent and the Grenadines','VCT','670');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('WS','SAMOA','Samoa','WSM','882');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SM','SAN MARINO','San Marino','SMR','674');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('ST','SAO TOME AND PRINCIPE','Sao Tome and Principe','STP','678');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SA','SAUDI ARABIA','Saudi Arabia','SAU','682');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SN','SENEGAL','Senegal','SEN','686');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CS','SERBIA AND MONTENEGRO','Serbia and Montenegro',NULL,NULL);");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SC','SEYCHELLES','Seychelles','SYC','690');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SL','SIERRA LEONE','Sierra Leone','SLE','694');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SG','SINGAPORE','Singapore','SGP','702');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SK','SLOVAKIA','Slovakia','SVK','703');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SI','SLOVENIA','Slovenia','SVN','705');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SB','SOLOMON ISLANDS','Solomon Islands','SLB','090');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SO','SOMALIA','Somalia','SOM','706');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('ZA','SOUTH AFRICA','South Africa','ZAF','710');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GS','SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS','South Georgia and the South Sandwich Islands',NULL,NULL);");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('ES','SPAIN','Spain','ESP','724');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('LK','SRI LANKA','Sri Lanka','LKA','144');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SD','SUDAN','Sudan','SDN','736');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SR','SURINAME','Suriname','SUR','740');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SJ','SVALBARD AND JAN MAYEN','Svalbard and Jan Mayen','SJM','744');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SZ','SWAZILAND','Swaziland','SWZ','748');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SE','SWEDEN','Sweden','SWE','752');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('CH','SWITZERLAND','Switzerland','CHE','756');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('SY','SYRIAN ARAB REPUBLIC','Syrian Arab Republic','SYR','760');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TW','TAIWAN, PROVINCE OF CHINA','Taiwan, Province of China','TWN','158');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TJ','TAJIKISTAN','Tajikistan','TJK','762');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TZ','TANZANIA, UNITED REPUBLIC OF','Tanzania, United Republic of','TZA','834');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TH','THAILAND','Thailand','THA','764');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TL','TIMOR-LESTE','Timor-Leste',NULL,NULL);");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TG','TOGO','Togo','TGO','768');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TK','TOKELAU','Tokelau','TKL','772')");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TO','TONGA','Tonga','TON','776');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TT','TRINIDAD AND TOBAGO','Trinidad and Tobago','TTO','780');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TN','TUNISIA','Tunisia','TUN','788');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TR','TURKEY','Turkey','TUR','792');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TM','TURKMENISTAN','Turkmenistan','TKM','795');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TC','TURKS AND CAICOS ISLANDS','Turks and Caicos Islands','TCA','796');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('TV','TUVALU','Tuvalu','TUV','798');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('UG','UGANDA','Uganda','UGA','800');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('UA','UKRAINE','Ukraine','UKR','804');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('AE','UNITED ARAB EMIRATES','United Arab Emirates','ARE','784');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('GB','UNITED KINGDOM','United Kingdom','GBR','826');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('US','UNITED STATES','United States','USA','840');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('UM','UNITED STATES MINOR OUTLYING ISLANDS','United States Minor Outlying Islands',NULL,NULL);");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('UY','URUGUAY','Uruguay','URY','858');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('UZ','UZBEKISTAN','Uzbekistan','UZB','860');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('VU','VANUATU','Vanuatu','VUT','548');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('VE','VENEZUELA','Venezuela','VEN','862');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('VN','VIET NAM','Viet Nam','VNM','704');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('VG','VIRGIN ISLANDS, BRITISH','Virgin Islands, British','VGB','092');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('VI','VIRGIN ISLANDS, U.S.','Virgin Islands, U.s.','VIR','850');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('WF','WALLIS AND FUTUNA','Wallis and Futuna','WLF','876');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('EH','WESTERN SAHARA','Western Sahara','ESH','732');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('YE','YEMEN','Yemen','YEM','887');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('ZM','ZAMBIA','Zambia','ZMB','894');");
		$this->db->query("INSERT INTO {$prefix}countries VALUES ('ZW','ZIMBABWE','Zimbabwe','ZWE','716');");
		
		
		// Add the country_id column into the users table
		$this->dbforge->add_column('users', array(
				'country_id'	=> array(
					'type'			=> 'INT',
					'constraint'	=> 11,
					'null'			=> TRUE
				)
			)
		);

		// Add the state_id column into the users table
		$this->dbforge->add_column('users', array(
				'state_id'	=> array(
					'type'			=> 'INT',
					'constraint'	=> 11,
					'null'			=> TRUE
				)
			)
		);
		
		// Map the state_code values to the state_id and update each user
		$sql = "SELECT * FROM {$prefix}states";
		$old_states_query = $this->db->query($sql);
		// loop through the current states
		$old_states_array = array();
		foreach ($old_states_query->result_array() as $state_rec)
		{
			$old_states_array[$state_rec['abbrev']] = $state_rec['id'];
		}

		$sql = "SELECT * FROM {$prefix}users";
		$users_query = $this->db->query($sql);
		// loop through the current users
		foreach ($users_query->result_array() as $user_rec)
		{
			
			if (!empty($user_rec['state_code']))
			{
				$this->db->query("UPDATE {$prefix}users SET `state_id` = '{$old_states_array[$user_rec['state_code']]}' WHERE `id` = '{$user_rec['id']}';");
			}
		}

		// Remove the state_code column from the users table
		$this->dbforge->drop_column('users', 'state_code');
	}
	
	//--------------------------------------------------------------------
	
}