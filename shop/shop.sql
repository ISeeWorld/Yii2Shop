DROP TABLE IF EXISTS  `shop_admin`;    
CREATE TABLE IF NOT EXISTS `shop_admin`(
     `adminid` INT UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'ID',
     `adminuser` VARCHAR(32) NOT NULL DEFAULT '' COMMENT 'name',
     `adminpass` CHAR(32) NOT NULL DEFAULT '' COMMENT 'password',
     `adminemail` VARCHAR(50) NOT NULL DEFAULT '' COMMENT 'email',
     `logintime` INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '登录时间',
     `loginip` BIGINT NOT NULL DEFAULT '0' COMMENT '登录IP',
     `createtime` INT UNSIGNED NOT NULL DEFAULT '0' COMMENT '创建时间',
     PRIMARY KEY(`adminid`),
     UNIQUE shop_admin_adminuser_adminpass(`adminuser`,`adminpass`),
     UNIQUE shop_admin_adminuser_adminemail(`adminuser`,`adminemail`)
)ENGINE=INNODB DEFAULT CHARSET=utf8;

INSERT INTO `shop_admin` (adminuser,adminpass,adminemail,createtime) VALUES ('root',md5('123'),'shop@imooc.com',UNIX_TIMESTAMP());



DROP TABLE IF EXISTS `shop_category`;
CREATE TABLE IF NOT EXISTS `shop_category`(
    `cateid` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(32) NOT NULL DEFAULT '',
    `parentid` BIGINT UNSIGNED NOT NULL DEFAULT '0',
    `createtime` INT UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY(`cateid`),
    KEY shop_category_parentid(`parentid`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

########################无限极分类数据表###############################

DROP TABLE IF EXISTS `shop_category` ;
CREATE TABLE IF NOT EXISTS `shop_category`(
    `cateid` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(32) NOT NULL DEFAULT '',
    `parentid` BIGINT UNSIGNED NOT NULL DEFAULT '0',
    `createtime` INT UNSIGNED NOT NULL DEFAULT '0',
    PRIMARY KEY(`cateid`),
    KEY shop_category_parentid(`parentid`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;