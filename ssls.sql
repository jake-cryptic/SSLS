CREATE TABLE `ssls` (
  `user_id` int(11) NOT NULL,
  `active` int(1) NOT NULL DEFAULT '0',
  `account_type` int(1) NOT NULL DEFAULT '0',
  `salt` varchar(128) NOT NULL,
  `password` varchar(128) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(100) NOT NULL,
  `sign_up` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_login` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `last_ip` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;


ALTER TABLE `ssls`
  ADD PRIMARY KEY (`user_id`);
