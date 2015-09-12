CREATE DATABASE IF NOT EXISTS `CRUDObjects_example` CHARACTER SET utf8 COLLATE utf8_general_ci;

USE `CRUDObjects_example`;

GRANT ALL ON CRUDObjects_example.* TO 'CRUDObjects_user'@'localhost' IDENTIFIED BY 'abc123';

CREATE TABLE IF NOT EXISTS `example_user` (
  `user_id` int(10) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `contact_email` varchar(45) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;


INSERT INTO `example_user` (`user_id`, `first_name`, `last_name`, `contact_email`) VALUES
(1, 'Richard', 'Hendricks', 'richard.hendricks@piedpiper.com'),
(2, 'Erlich', 'Bachmann', 'erlich.bachmann@piedpiper.com'),
(3, 'Bertram', 'Gilfoyle', 'bertram.gilfoyle@piedpiper.com');

COMMIT;


