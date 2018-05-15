<?php

/*
 * SVN Access Manager - a subversion access rights management tool
 * Copyright (C) 2008-2018 Thomas Krieger <tom@svn-access-manager.org>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */

/*
 *
 * $LastChangedDate$
 * $LastChangedBy$
 *
 * $Id$
 *
 */
final class FunctionsTest extends PHPUnit_Framework_TestCase {

    public function testcheck_string() {

        include_once 'functions.inc.php';
        $this->assertEquals(true, check_string("AGBVFG5654rtref"));
        $this->assertEquals(false, check_string("AGBVFG565\><4rtref"));
        
    }

    public function testcheck_date() {

        include_once 'functions.inc.php';
        
        $this->assertTrue(check_date('12', '12', '2017'));
        $this->assertFalse(check_date('30', '02', '2017'));
        $this->assertFalse(check_date('3a', '03', '2017'));
        $this->assertFalse(check_date('28', 'a3', '2017'));
        $this->assertFalse(check_date('24', '03', '2a17'));
        
    }

    public function testsplitdate() {

        include_once 'functions.inc.php';
        
        $this->assertEquals('11.12.2017', splitdate('20171211'));
        
    }

    public function testGetPhpVersion() {

        $this->assertGreaterThan('50', getPhpVersion());
        
    }

    public function testSplitDateTime() {

        list($date, $time ) = splitDateTime('20160302101112');
        $this->assertEquals('02.03.2016', $date);
        $this->assertEquals('10:11:12', $time);
        
    }

    public function testSplitValiddate() {

        include_once 'functions.inc.php';
        
        $this->assertEquals('11.12.2017', splitValidDate('20171211'));
        
    }

    public function testMkUnixTimestampFromDateTime() {

        $this->assertEquals(1456909872, mkUnixTimestampFromDateTime('20160302101112'));
        
    }

    public function testNoMagicQuotes() {

        $this->assertEquals('SELECT * FROM `eurokurse` WHERE kurs like \%;', no_magic_quotes('SELECT * FROM `eurokurse` WHERE kurs like \%;'));
        
    }

    public function test_getLocale() {

        include_once 'functions.inc.php';
        
        $this->assertEquals('en_US', get_locale());
        
    }

    public function test_initialize_i18n() {

        include_once 'functions.inc.php';
        
        $this->assertNull(initialize_i18n());
        
    }

    public function test_checkLanguage() {

        include_once 'functions.inc.php';
        
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'de_DE';
        $this->assertEquals('de', check_language());
        
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = 'en_US';
        $this->assertEquals('en', check_language());
        
    }

    public function test_getDateJhjjmmtt() {

        $this->assertEquals(date('Ymd'), getDateJhjjmmtt());
        
    }

    public function test_no_magic_quotes() {

        $this->assertEquals('SELECT * FROM test;', no_magic_quotes('SELECT * FROM test;'));
        
    }

    public function test_digestcrypt() {

        $this->assertEquals('81d477272475b5bcf5fe5659c7b1d05d', digestcrypt('admin', 'huibuh', '1234567890'));
        
    }

    public function test_checkPasswordPolicy() {

        $this->assertEquals(1, checkPasswordPolicy('Start!1234567_', 'y'));
        $this->assertEquals(0, checkPasswordPolicy('123jhy456jfhg7', 'y'));
        $this->assertEquals(0, checkPasswordPolicy('Start!123', 'y'));
        
        $this->assertEquals(1, checkPasswordPolicy('Start!12', 'n'));
        $this->assertEquals(0, checkPasswordPolicy('123jhy45', 'n'));
        $this->assertEquals(0, checkPasswordPolicy('Start!', 'n'));
        
    }

    public function test_check_email() {

        $emailAddresses = array(
                'q@q.com' => true,
                'test.user@test.aaa' => true,
                'test.user@test.aarp' => true,
                'test.user@test.abarth' => true,
                'test.user@test.abb' => true,
                'test.user@test.abbott' => true,
                'test.user@test.abbvie' => true,
                'test.user@test.abc' => true,
                'test.user@test.able' => true,
                'test.user@test.abogado' => true,
                'test.user@test.abudhabi' => true,
                'test.user@test.ac' => true,
                'test.user@test.academy' => true,
                'test.user@test.accenture' => true,
                'test.user@test.accountant' => true,
                'test.user@test.accountants' => true,
                'test.user@test.aco' => true,
                'test.user@test.active' => true,
                'test.user@test.actor' => true,
                'test.user@test.ad' => true,
                'test.user@test.adac' => true,
                'test.user@test.ads' => true,
                'test.user@test.adult' => true,
                'test.user@test.ae' => true,
                'test.user@test.aeg' => true,
                'test.user@test.aero' => true,
                'test.user@test.aetna' => true,
                'test.user@test.af' => true,
                'test.user@test.afamilycompany' => true,
                'test.user@test.afl' => true,
                'test.user@test.africa' => true,
                'test.user@test.ag' => true,
                'test.user@test.agakhan' => true,
                'test.user@test.agency' => true,
                'test.user@test.ai' => true,
                'test.user@test.aig' => true,
                'test.user@test.aigo' => true,
                'test.user@test.airbus' => true,
                'test.user@test.airforce' => true,
                'test.user@test.airtel' => true,
                'test.user@test.akdn' => true,
                'test.user@test.al' => true,
                'test.user@test.alfaromeo' => true,
                'test.user@test.alibaba' => true,
                'test.user@test.alipay' => true,
                'test.user@test.allfinanz' => true,
                'test.user@test.allstate' => true,
                'test.user@test.ally' => true,
                'test.user@test.alsace' => true,
                'test.user@test.alstom' => true,
                'test.user@test.am' => true,
                'test.user@test.americanexpress' => true,
                'test.user@test.americanfamily' => true,
                'test.user@test.amex' => true,
                'test.user@test.amfam' => true,
                'test.user@test.amica' => true,
                'test.user@test.amsterdam' => true,
                'test.user@test.analytics' => true,
                'test.user@test.android' => true,
                'test.user@test.anquan' => true,
                'test.user@test.anz' => true,
                'test.user@test.ao' => true,
                'test.user@test.aol' => true,
                'test.user@test.apartments' => true,
                'test.user@test.app' => true,
                'test.user@test.apple' => true,
                'test.user@test.aq' => true,
                'test.user@test.aquarelle' => true,
                'test.user@test.ar' => true,
                'test.user@test.arab' => true,
                'test.user@test.aramco' => true,
                'test.user@test.archi' => true,
                'test.user@test.army' => true,
                'test.user@test.arpa' => true,
                'test.user@test.art' => true,
                'test.user@test.arte' => true,
                'test.user@test.as' => true,
                'test.user@test.asda' => true,
                'test.user@test.asia' => true,
                'test.user@test.associates' => true,
                'test.user@test.at' => true,
                'test.user@test.athleta' => true,
                'test.user@test.attorney' => true,
                'test.user@test.au' => true,
                'test.user@test.auction' => true,
                'test.user@test.audi' => true,
                'test.user@test.audible' => true,
                'test.user@test.audio' => true,
                'test.user@test.auspost' => true,
                'test.user@test.author' => true,
                'test.user@test.auto' => true,
                'test.user@test.autos' => true,
                'test.user@test.avianca' => true,
                'test.user@test.aw' => true,
                'test.user@test.aws' => true,
                'test.user@test.ax' => true,
                'test.user@test.axa' => true,
                'test.user@test.az' => true,
                'test.user@test.azure' => true,
                'test.user@test.ba' => true,
                'test.user@test.baby' => true,
                'test.user@test.baidu' => true,
                'test.user@test.banamex' => true,
                'test.user@test.bananarepublic' => true,
                'test.user@test.band' => true,
                'test.user@test.bank' => true,
                'test.user@test.bar' => true,
                'test.user@test.barcelona' => true,
                'test.user@test.barclaycard' => true,
                'test.user@test.barclays' => true,
                'test.user@test.barefoot' => true,
                'test.user@test.bargains' => true,
                'test.user@test.baseball' => true,
                'test.user@test.basketball' => true,
                'test.user@test.bauhaus' => true,
                'test.user@test.bayern' => true,
                'test.user@test.bb' => true,
                'test.user@test.bbc' => true,
                'test.user@test.bbt' => true,
                'test.user@test.bbva' => true,
                'test.user@test.bcg' => true,
                'test.user@test.bcn' => true,
                'test.user@test.bd' => true,
                'test.user@test.be' => true,
                'test.user@test.beats' => true,
                'test.user@test.beauty' => true,
                'test.user@test.beer' => true,
                'test.user@test.bentley' => true,
                'test.user@test.berlin' => true,
                'test.user@test.best' => true,
                'test.user@test.bestbuy' => true,
                'test.user@test.bet' => true,
                'test.user@test.bf' => true,
                'test.user@test.bg' => true,
                'test.user@test.bh' => true,
                'test.user@test.bharti' => true,
                'test.user@test.bi' => true,
                'test.user@test.bible' => true,
                'test.user@test.bid' => true,
                'test.user@test.bike' => true,
                'test.user@test.bing' => true,
                'test.user@test.bingo' => true,
                'test.user@test.bio' => true,
                'test.user@test.biz' => true,
                'test.user@test.bj' => true,
                'test.user@test.black' => true,
                'test.user@test.blackfriday' => true,
                'test.user@test.blanco' => true,
                'test.user@test.blockbuster' => true,
                'test.user@test.blog' => true,
                'test.user@test.bloomberg' => true,
                'test.user@test.blue' => true,
                'test.user@test.bm' => true,
                'test.user@test.bms' => true,
                'test.user@test.bmw' => true,
                'test.user@test.bn' => true,
                'test.user@test.bnl' => true,
                'test.user@test.bnpparibas' => true,
                'test.user@test.bo' => true,
                'test.user@test.boats' => true,
                'test.user@test.boehringer' => true,
                'test.user@test.bofa' => true,
                'test.user@test.bom' => true,
                'test.user@test.bond' => true,
                'test.user@test.boo' => true,
                'test.user@test.book' => true,
                'test.user@test.booking' => true,
                'test.user@test.boots' => true,
                'test.user@test.bosch' => true,
                'test.user@test.bostik' => true,
                'test.user@test.boston' => true,
                'test.user@test.bot' => true,
                'test.user@test.boutique' => true,
                'test.user@test.box' => true,
                'test.user@test.br' => true,
                'test.user@test.bradesco' => true,
                'test.user@test.bridgestone' => true,
                'test.user@test.broadway' => true,
                'test.user@test.broker' => true,
                'test.user@test.brother' => true,
                'test.user@test.brussels' => true,
                'test.user@test.bs' => true,
                'test.user@test.bt' => true,
                'test.user@test.budapest' => true,
                'test.user@test.bugatti' => true,
                'test.user@test.build' => true,
                'test.user@test.builders' => true,
                'test.user@test.business' => true,
                'test.user@test.buy' => true,
                'test.user@test.buzz' => true,
                'test.user@test.bv' => true,
                'test.user@test.bw' => true,
                'test.user@test.by' => true,
                'test.user@test.bz' => true,
                'test.user@test.bzh' => true,
                'test.user@test.ca' => true,
                'test.user@test.cab' => true,
                'test.user@test.cafe' => true,
                'test.user@test.cal' => true,
                'test.user@test.call' => true,
                'test.user@test.calvinklein' => true,
                'test.user@test.cam' => true,
                'test.user@test.camera' => true,
                'test.user@test.camp' => true,
                'test.user@test.cancerresearch' => true,
                'test.user@test.canon' => true,
                'test.user@test.capetown' => true,
                'test.user@test.capital' => true,
                'test.user@test.capitalone' => true,
                'test.user@test.car' => true,
                'test.user@test.caravan' => true,
                'test.user@test.cards' => true,
                'test.user@test.care' => true,
                'test.user@test.career' => true,
                'test.user@test.careers' => true,
                'test.user@test.cars' => true,
                'test.user@test.cartier' => true,
                'test.user@test.casa' => true,
                'test.user@test.case' => true,
                'test.user@test.caseih' => true,
                'test.user@test.cash' => true,
                'test.user@test.casino' => true,
                'test.user@test.cat' => true,
                'test.user@test.catering' => true,
                'test.user@test.catholic' => true,
                'test.user@test.cba' => true,
                'test.user@test.cbn' => true,
                'test.user@test.cbre' => true,
                'test.user@test.cbs' => true,
                'test.user@test.cc' => true,
                'test.user@test.cd' => true,
                'test.user@test.ceb' => true,
                'test.user@test.center' => true,
                'test.user@test.ceo' => true,
                'test.user@test.cern' => true,
                'test.user@test.cf' => true,
                'test.user@test.cfa' => true,
                'test.user@test.cfd' => true,
                'test.user@test.cg' => true,
                'test.user@test.ch' => true,
                'test.user@test.chanel' => true,
                'test.user@test.channel' => true,
                'test.user@test.chase' => true,
                'test.user@test.chat' => true,
                'test.user@test.cheap' => true,
                'test.user@test.chintai' => true,
                'test.user@test.christmas' => true,
                'test.user@test.chrome' => true,
                'test.user@test.chrysler' => true,
                'test.user@test.church' => true,
                'test.user@test.ci' => true,
                'test.user@test.cipriani' => true,
                'test.user@test.circle' => true,
                'test.user@test.cisco' => true,
                'test.user@test.citadel' => true,
                'test.user@test.citi' => true,
                'test.user@test.citic' => true,
                'test.user@test.city' => true,
                'test.user@test.cityeats' => true,
                'test.user@test.ck' => true,
                'test.user@test.cl' => true,
                'test.user@test.claims' => true,
                'test.user@test.cleaning' => true,
                'test.user@test.click' => true,
                'test.user@test.clinic' => true,
                'test.user@test.clinique' => true,
                'test.user@test.clothing' => true,
                'test.user@test.cloud' => true,
                'test.user@test.club' => true,
                'test.user@test.clubmed' => true,
                'test.user@test.cm' => true,
                'test.user@test.cn' => true,
                'test.user@test.co' => true,
                'test.user@test.coach' => true,
                'test.user@test.codes' => true,
                'test.user@test.coffee' => true,
                'test.user@test.college' => true,
                'test.user@test.cologne' => true,
                'test.user@test.com' => true,
                'test.user@test.comcast' => true,
                'test.user@test.commbank' => true,
                'test.user@test.community' => true,
                'test.user@test.company' => true,
                'test.user@test.compare' => true,
                'test.user@test.computer' => true,
                'test.user@test.comsec' => true,
                'test.user@test.condos' => true,
                'test.user@test.construction' => true,
                'test.user@test.consulting' => true,
                'test.user@test.contact' => true,
                'test.user@test.contractors' => true,
                'test.user@test.cooking' => true,
                'test.user@test.cookingchannel' => true,
                'test.user@test.cool' => true,
                'test.user@test.coop' => true,
                'test.user@test.corsica' => true,
                'test.user@test.country' => true,
                'test.user@test.coupon' => true,
                'test.user@test.coupons' => true,
                'test.user@test.courses' => true,
                'test.user@test.cr' => true,
                'test.user@test.credit' => true,
                'test.user@test.creditcard' => true,
                'test.user@test.creditunion' => true,
                'test.user@test.cricket' => true,
                'test.user@test.crown' => true,
                'test.user@test.crs' => true,
                'test.user@test.cruise' => true,
                'test.user@test.cruises' => true,
                'test.user@test.csc' => true,
                'test.user@test.cu' => true,
                'test.user@test.cuisinella' => true,
                'test.user@test.cv' => true,
                'test.user@test.cw' => true,
                'test.user@test.cx' => true,
                'test.user@test.cy' => true,
                'test.user@test.cymru' => true,
                'test.user@test.cyou' => true,
                'test.user@test.cz' => true,
                'test.user@test.dabur' => true,
                'test.user@test.dad' => true,
                'test.user@test.dance' => true,
                'test.user@test.data' => true,
                'test.user@test.date' => true,
                'test.user@test.dating' => true,
                'test.user@test.datsun' => true,
                'test.user@test.day' => true,
                'test.user@test.dclk' => true,
                'test.user@test.dds' => true,
                'test.user@test.de' => true,
                'test.user@test.deal' => true,
                'test.user@test.dealer' => true,
                'test.user@test.deals' => true,
                'test.user@test.degree' => true,
                'test.user@test.delivery' => true,
                'test.user@test.dell' => true,
                'test.user@test.deloitte' => true,
                'test.user@test.delta' => true,
                'test.user@test.democrat' => true,
                'test.user@test.dental' => true,
                'test.user@test.dentist' => true,
                'test.user@test.desi' => true,
                'test.user@test.design' => true,
                'test.user@test.dev' => true,
                'test.user@test.dhl' => true,
                'test.user@test.diamonds' => true,
                'test.user@test.diet' => true,
                'test.user@test.digital' => true,
                'test.user@test.direct' => true,
                'test.user@test.directory' => true,
                'test.user@test.discount' => true,
                'test.user@test.discover' => true,
                'test.user@test.dish' => true,
                'test.user@test.diy' => true,
                'test.user@test.dj' => true,
                'test.user@test.dk' => true,
                'test.user@test.dm' => true,
                'test.user@test.dnp' => true,
                'test.user@test.do' => true,
                'test.user@test.docs' => true,
                'test.user@test.doctor' => true,
                'test.user@test.dodge' => true,
                'test.user@test.dog' => true,
                'test.user@test.doha' => true,
                'test.user@test.domains' => true,
                'test.user@test.dot' => true,
                'test.user@test.download' => true,
                'test.user@test.drive' => true,
                'test.user@test.dtv' => true,
                'test.user@test.dubai' => true,
                'test.user@test.duck' => true,
                'test.user@test.dunlop' => true,
                'test.user@test.duns' => true,
                'test.user@test.dupont' => true,
                'test.user@test.durban' => true,
                'test.user@test.dvag' => true,
                'test.user@test.dvr' => true,
                'test.user@test.dz' => true,
                'test.user@test.earth' => true,
                'test.user@test.eat' => true,
                'test.user@test.ec' => true,
                'test.user@test.eco' => true,
                'test.user@test.edeka' => true,
                'test.user@test.edu' => true,
                'test.user@test.education' => true,
                'test.user@test.ee' => true,
                'test.user@test.eg' => true,
                'test.user@test.email' => true,
                'test.user@test.emerck' => true,
                'test.user@test.energy' => true,
                'test.user@test.engineer' => true,
                'test.user@test.engineering' => true,
                'test.user@test.enterprises' => true,
                'test.user@test.epost' => true,
                'test.user@test.epson' => true,
                'test.user@test.equipment' => true,
                'test.user@test.er' => true,
                'test.user@test.ericsson' => true,
                'test.user@test.erni' => true,
                'test.user@test.es' => true,
                'test.user@test.esq' => true,
                'test.user@test.estate' => true,
                'test.user@test.esurance' => true,
                'test.user@test.et' => true,
                'test.user@test.etisalat' => true,
                'test.user@test.eu' => true,
                'test.user@test.eurovision' => true,
                'test.user@test.eus' => true,
                'test.user@test.events' => true,
                'test.user@test.everbank' => true,
                'test.user@test.exchange' => true,
                'test.user@test.expert' => true,
                'test.user@test.exposed' => true,
                'test.user@test.express' => true,
                'test.user@test.extraspace' => true,
                'test.user@test.fage' => true,
                'test.user@test.fail' => true,
                'test.user@test.fairwinds' => true,
                'test.user@test.faith' => true,
                'test.user@test.family' => true,
                'test.user@test.fan' => true,
                'test.user@test.fans' => true,
                'test.user@test.farm' => true,
                'test.user@test.farmers' => true,
                'test.user@test.fashion' => true,
                'test.user@test.fast' => true,
                'test.user@test.fedex' => true,
                'test.user@test.feedback' => true,
                'test.user@test.ferrari' => true,
                'test.user@test.ferrero' => true,
                'test.user@test.fi' => true,
                'test.user@test.fiat' => true,
                'test.user@test.fidelity' => true,
                'test.user@test.fido' => true,
                'test.user@test.film' => true,
                'test.user@test.final' => true,
                'test.user@test.finance' => true,
                'test.user@test.financial' => true,
                'test.user@test.fire' => true,
                'test.user@test.firestone' => true,
                'test.user@test.firmdale' => true,
                'test.user@test.fish' => true,
                'test.user@test.fishing' => true,
                'test.user@test.fit' => true,
                'test.user@test.fitness' => true,
                'test.user@test.fj' => true,
                'test.user@test.fk' => true,
                'test.user@test.flickr' => true,
                'test.user@test.flights' => true,
                'test.user@test.flir' => true,
                'test.user@test.florist' => true,
                'test.user@test.flowers' => true,
                'test.user@test.fly' => true,
                'test.user@test.fm' => true,
                'test.user@test.fo' => true,
                'test.user@test.foo' => true,
                'test.user@test.food' => true,
                'test.user@test.foodnetwork' => true,
                'test.user@test.football' => true,
                'test.user@test.ford' => true,
                'test.user@test.forex' => true,
                'test.user@test.forsale' => true,
                'test.user@test.forum' => true,
                'test.user@test.foundation' => true,
                'test.user@test.fox' => true,
                'test.user@test.fr' => true,
                'test.user@test.free' => true,
                'test.user@test.fresenius' => true,
                'test.user@test.frl' => true,
                'test.user@test.frogans' => true,
                'test.user@test.frontdoor' => true,
                'test.user@test.frontier' => true,
                'test.user@test.ftr' => true,
                'test.user@test.fujitsu' => true,
                'test.user@test.fujixerox' => true,
                'test.user@test.fun' => true,
                'test.user@test.fund' => true,
                'test.user@test.furniture' => true,
                'test.user@test.futbol' => true,
                'test.user@test.fyi' => true,
                'test.user@test.ga' => true,
                'test.user@test.gal' => true,
                'test.user@test.gallery' => true,
                'test.user@test.gallo' => true,
                'test.user@test.gallup' => true,
                'test.user@test.game' => true,
                'test.user@test.games' => true,
                'test.user@test.gap' => true,
                'test.user@test.garden' => true,
                'test.user@test.gb' => true,
                'test.user@test.gbiz' => true,
                'test.user@test.gd' => true,
                'test.user@test.gdn' => true,
                'test.user@test.ge' => true,
                'test.user@test.gea' => true,
                'test.user@test.gent' => true,
                'test.user@test.genting' => true,
                'test.user@test.george' => true,
                'test.user@test.gf' => true,
                'test.user@test.gg' => true,
                'test.user@test.ggee' => true,
                'test.user@test.gh' => true,
                'test.user@test.gi' => true,
                'test.user@test.gift' => true,
                'test.user@test.gifts' => true,
                'test.user@test.gives' => true,
                'test.user@test.giving' => true,
                'test.user@test.gl' => true,
                'test.user@test.glade' => true,
                'test.user@test.glass' => true,
                'test.user@test.gle' => true,
                'test.user@test.global' => true,
                'test.user@test.globo' => true,
                'test.user@test.gm' => true,
                'test.user@test.gmail' => true,
                'test.user@test.gmbh' => true,
                'test.user@test.gmo' => true,
                'test.user@test.gmx' => true,
                'test.user@test.gn' => true,
                'test.user@test.godaddy' => true,
                'test.user@test.gold' => true,
                'test.user@test.goldpoint' => true,
                'test.user@test.golf' => true,
                'test.user@test.goo' => true,
                'test.user@test.goodhands' => true,
                'test.user@test.goodyear' => true,
                'test.user@test.goog' => true,
                'test.user@test.google' => true,
                'test.user@test.gop' => true,
                'test.user@test.got' => true,
                'test.user@test.gov' => true,
                'test.user@test.gp' => true,
                'test.user@test.gq' => true,
                'test.user@test.gr' => true,
                'test.user@test.grainger' => true,
                'test.user@test.graphics' => true,
                'test.user@test.gratis' => true,
                'test.user@test.green' => true,
                'test.user@test.gripe' => true,
                'test.user@test.grocery' => true,
                'test.user@test.group' => true,
                'test.user@test.gs' => true,
                'test.user@test.gt' => true,
                'test.user@test.gu' => true,
                'test.user@test.guardian' => true,
                'test.user@test.gucci' => true,
                'test.user@test.guge' => true,
                'test.user@test.guide' => true,
                'test.user@test.guitars' => true,
                'test.user@test.guru' => true,
                'test.user@test.gw' => true,
                'test.user@test.gy' => true,
                'test.user@test.hair' => true,
                'test.user@test.hamburg' => true,
                'test.user@test.hangout' => true,
                'test.user@test.haus' => true,
                'test.user@test.hbo' => true,
                'test.user@test.hdfc' => true,
                'test.user@test.hdfcbank' => true,
                'test.user@test.health' => true,
                'test.user@test.healthcare' => true,
                'test.user@test.help' => true,
                'test.user@test.helsinki' => true,
                'test.user@test.here' => true,
                'test.user@test.hermes' => true,
                'test.user@test.hgtv' => true,
                'test.user@test.hiphop' => true,
                'test.user@test.hisamitsu' => true,
                'test.user@test.hitachi' => true,
                'test.user@test.hiv' => true,
                'test.user@test.hk' => true,
                'test.user@test.hkt' => true,
                'test.user@test.hm' => true,
                'test.user@test.hn' => true,
                'test.user@test.hockey' => true,
                'test.user@test.holdings' => true,
                'test.user@test.holiday' => true,
                'test.user@test.homedepot' => true,
                'test.user@test.homegoods' => true,
                'test.user@test.homes' => true,
                'test.user@test.homesense' => true,
                'test.user@test.honda' => true,
                'test.user@test.honeywell' => true,
                'test.user@test.horse' => true,
                'test.user@test.hospital' => true,
                'test.user@test.host' => true,
                'test.user@test.hosting' => true,
                'test.user@test.hot' => true,
                'test.user@test.hoteles' => true,
                'test.user@test.hotels' => true,
                'test.user@test.hotmail' => true,
                'test.user@test.house' => true,
                'test.user@test.how' => true,
                'test.user@test.hr' => true,
                'test.user@test.hsbc' => true,
                'test.user@test.ht' => true,
                'test.user@test.hu' => true,
                'test.user@test.hughes' => true,
                'test.user@test.hyatt' => true,
                'test.user@test.hyundai' => true,
                'test.user@test.ibm' => true,
                'test.user@test.icbc' => true,
                'test.user@test.ice' => true,
                'test.user@test.icu' => true,
                'test.user@test.id' => true,
                'test.user@test.ie' => true,
                'test.user@test.ieee' => true,
                'test.user@test.ifm' => true,
                'test.user@test.ikano' => true,
                'test.user@test.il' => true,
                'test.user@test.im' => true,
                'test.user@test.imamat' => true,
                'test.user@test.imdb' => true,
                'test.user@test.immo' => true,
                'test.user@test.immobilien' => true,
                'test.user@test.in' => true,
                'test.user@test.industries' => true,
                'test.user@test.infiniti' => true,
                'test.user@test.info' => true,
                'test.user@test.ing' => true,
                'test.user@test.ink' => true,
                'test.user@test.institute' => true,
                'test.user@test.insurance' => true,
                'test.user@test.insure' => true,
                'test.user@test.int' => true,
                'test.user@test.intel' => true,
                'test.user@test.international' => true,
                'test.user@test.intuit' => true,
                'test.user@test.investments' => true,
                'test.user@test.io' => true,
                'test.user@test.ipiranga' => true,
                'test.user@test.iq' => true,
                'test.user@test.ir' => true,
                'test.user@test.irish' => true,
                'test.user@test.is' => true,
                'test.user@test.iselect' => true,
                'test.user@test.ismaili' => true,
                'test.user@test.ist' => true,
                'test.user@test.istanbul' => true,
                'test.user@test.it' => true,
                'test.user@test.itau' => true,
                'test.user@test.itv' => true,
                'test.user@test.iveco' => true,
                'test.user@test.iwc' => true,
                'test.user@test.jaguar' => true,
                'test.user@test.java' => true,
                'test.user@test.jcb' => true,
                'test.user@test.jcp' => true,
                'test.user@test.je' => true,
                'test.user@test.jeep' => true,
                'test.user@test.jetzt' => true,
                'test.user@test.jewelry' => true,
                'test.user@test.jio' => true,
                'test.user@test.jlc' => true,
                'test.user@test.jll' => true,
                'test.user@test.jm' => true,
                'test.user@test.jmp' => true,
                'test.user@test.jnj' => true,
                'test.user@test.jo' => true,
                'test.user@test.jobs' => true,
                'test.user@test.joburg' => true,
                'test.user@test.jot' => true,
                'test.user@test.joy' => true,
                'test.user@test.jp' => true,
                'test.user@test.jpmorgan' => true,
                'test.user@test.jprs' => true,
                'test.user@test.juegos' => true,
                'test.user@test.juniper' => true,
                'test.user@test.kaufen' => true,
                'test.user@test.kddi' => true,
                'test.user@test.ke' => true,
                'test.user@test.kerryhotels' => true,
                'test.user@test.kerrylogistics' => true,
                'test.user@test.kerryproperties' => true,
                'test.user@test.kfh' => true,
                'test.user@test.kg' => true,
                'test.user@test.kh' => true,
                'test.user@test.ki' => true,
                'test.user@test.kia' => true,
                'test.user@test.kim' => true,
                'test.user@test.kinder' => true,
                'test.user@test.kindle' => true,
                'test.user@test.kitchen' => true,
                'test.user@test.kiwi' => true,
                'test.user@test.km' => true,
                'test.user@test.kn' => true,
                'test.user@test.koeln' => true,
                'test.user@test.komatsu' => true,
                'test.user@test.kosher' => true,
                'test.user@test.kp' => true,
                'test.user@test.kpmg' => true,
                'test.user@test.kpn' => true,
                'test.user@test.kr' => true,
                'test.user@test.krd' => true,
                'test.user@test.kred' => true,
                'test.user@test.kuokgroup' => true,
                'test.user@test.kw' => true,
                'test.user@test.ky' => true,
                'test.user@test.kyoto' => true,
                'test.user@test.kz' => true,
                'test.user@test.la' => true,
                'test.user@test.lacaixa' => true,
                'test.user@test.ladbrokes' => true,
                'test.user@test.lamborghini' => true,
                'test.user@test.lamer' => true,
                'test.user@test.lancaster' => true,
                'test.user@test.lancia' => true,
                'test.user@test.lancome' => true,
                'test.user@test.land' => true,
                'test.user@test.landrover' => true,
                'test.user@test.lanxess' => true,
                'test.user@test.lasalle' => true,
                'test.user@test.lat' => true,
                'test.user@test.latino' => true,
                'test.user@test.latrobe' => true,
                'test.user@test.law' => true,
                'test.user@test.lawyer' => true,
                'test.user@test.lb' => true,
                'test.user@test.lc' => true,
                'test.user@test.lds' => true,
                'test.user@test.lease' => true,
                'test.user@test.leclerc' => true,
                'test.user@test.lefrak' => true,
                'test.user@test.legal' => true,
                'test.user@test.lego' => true,
                'test.user@test.lexus' => true,
                'test.user@test.lgbt' => true,
                'test.user@test.li' => true,
                'test.user@test.liaison' => true,
                'test.user@test.lidl' => true,
                'test.user@test.life' => true,
                'test.user@test.lifeinsurance' => true,
                'test.user@test.lifestyle' => true,
                'test.user@test.lighting' => true,
                'test.user@test.like' => true,
                'test.user@test.lilly' => true,
                'test.user@test.limited' => true,
                'test.user@test.limo' => true,
                'test.user@test.lincoln' => true,
                'test.user@test.linde' => true,
                'test.user@test.link' => true,
                'test.user@test.lipsy' => true,
                'test.user@test.live' => true,
                'test.user@test.living' => true,
                'test.user@test.lixil' => true,
                'test.user@test.lk' => true,
                'test.user@test.llc' => true,
                'test.user@test.loan' => true,
                'test.user@test.loans' => true,
                'test.user@test.locker' => true,
                'test.user@test.locus' => true,
                'test.user@test.loft' => true,
                'test.user@test.lol' => true,
                'test.user@test.london' => true,
                'test.user@test.lotte' => true,
                'test.user@test.lotto' => true,
                'test.user@test.love' => true,
                'test.user@test.lpl' => true,
                'test.user@test.lplfinancial' => true,
                'test.user@test.lr' => true,
                'test.user@test.ls' => true,
                'test.user@test.lt' => true,
                'test.user@test.ltd' => true,
                'test.user@test.ltda' => true,
                'test.user@test.lu' => true,
                'test.user@test.lundbeck' => true,
                'test.user@test.lupin' => true,
                'test.user@test.luxe' => true,
                'test.user@test.luxury' => true,
                'test.user@test.lv' => true,
                'test.user@test.ly' => true,
                'test.user@test.ma' => true,
                'test.user@test.macys' => true,
                'test.user@test.madrid' => true,
                'test.user@test.maif' => true,
                'test.user@test.maison' => true,
                'test.user@test.makeup' => true,
                'test.user@test.man' => true,
                'test.user@test.management' => true,
                'test.user@test.mango' => true,
                'test.user@test.map' => true,
                'test.user@test.market' => true,
                'test.user@test.marketing' => true,
                'test.user@test.markets' => true,
                'test.user@test.marriott' => true,
                'test.user@test.marshalls' => true,
                'test.user@test.maserati' => true,
                'test.user@test.mattel' => true,
                'test.user@test.mba' => true,
                'test.user@test.mc' => true,
                'test.user@test.mckinsey' => true,
                'test.user@test.md' => true,
                'test.user@test.me' => true,
                'test.user@test.med' => true,
                'test.user@test.media' => true,
                'test.user@test.meet' => true,
                'test.user@test.melbourne' => true,
                'test.user@test.meme' => true,
                'test.user@test.memorial' => true,
                'test.user@test.men' => true,
                'test.user@test.menu' => true,
                'test.user@test.meo' => true,
                'test.user@test.merckmsd' => true,
                'test.user@test.metlife' => true,
                'test.user@test.mg' => true,
                'test.user@test.mh' => true,
                'test.user@test.miami' => true,
                'test.user@test.microsoft' => true,
                'test.user@test.mil' => true,
                'test.user@test.mini' => true,
                'test.user@test.mint' => true,
                'test.user@test.mit' => true,
                'test.user@test.mitsubishi' => true,
                'test.user@test.mk' => true,
                'test.user@test.ml' => true,
                'test.user@test.mlb' => true,
                'test.user@test.mls' => true,
                'test.user@test.mm' => true,
                'test.user@test.mma' => true,
                'test.user@test.mn' => true,
                'test.user@test.mo' => true,
                'test.user@test.mobi' => true,
                'test.user@test.mobile' => true,
                'test.user@test.mobily' => true,
                'test.user@test.moda' => true,
                'test.user@test.moe' => true,
                'test.user@test.moi' => true,
                'test.user@test.mom' => true,
                'test.user@test.monash' => true,
                'test.user@test.money' => true,
                'test.user@test.monster' => true,
                'test.user@test.mopar' => true,
                'test.user@test.mormon' => true,
                'test.user@test.mortgage' => true,
                'test.user@test.moscow' => true,
                'test.user@test.moto' => true,
                'test.user@test.motorcycles' => true,
                'test.user@test.mov' => true,
                'test.user@test.movie' => true,
                'test.user@test.movistar' => true,
                'test.user@test.mp' => true,
                'test.user@test.mq' => true,
                'test.user@test.mr' => true,
                'test.user@test.ms' => true,
                'test.user@test.msd' => true,
                'test.user@test.mt' => true,
                'test.user@test.mtn' => true,
                'test.user@test.mtr' => true,
                'test.user@test.mu' => true,
                'test.user@test.museum' => true,
                'test.user@test.mutual' => true,
                'test.user@test.mv' => true,
                'test.user@test.mw' => true,
                'test.user@test.mx' => true,
                'test.user@test.my' => true,
                'test.user@test.mz' => true,
                'test.user@test.na' => true,
                'test.user@test.nab' => true,
                'test.user@test.nadex' => true,
                'test.user@test.nagoya' => true,
                'test.user@test.name' => true,
                'test.user@test.nationwide' => true,
                'test.user@test.natura' => true,
                'test.user@test.navy' => true,
                'test.user@test.nba' => true,
                'test.user@test.nc' => true,
                'test.user@test.ne' => true,
                'test.user@test.nec' => true,
                'test.user@test.net' => true,
                'test.user@test.netbank' => true,
                'test.user@test.netflix' => true,
                'test.user@test.network' => true,
                'test.user@test.neustar' => true,
                'test.user@test.new' => true,
                'test.user@test.newholland' => true,
                'test.user@test.news' => true,
                'test.user@test.next' => true,
                'test.user@test.nextdirect' => true,
                'test.user@test.nexus' => true,
                'test.user@test.nf' => true,
                'test.user@test.nfl' => true,
                'test.user@test.ng' => true,
                'test.user@test.ngo' => true,
                'test.user@test.nhk' => true,
                'test.user@test.ni' => true,
                'test.user@test.nico' => true,
                'test.user@test.nike' => true,
                'test.user@test.nikon' => true,
                'test.user@test.ninja' => true,
                'test.user@test.nissan' => true,
                'test.user@test.nissay' => true,
                'test.user@test.nl' => true,
                'test.user@test.no' => true,
                'test.user@test.nokia' => true,
                'test.user@test.northwesternmutual' => true,
                'test.user@test.norton' => true,
                'test.user@test.now' => true,
                'test.user@test.nowruz' => true,
                'test.user@test.nowtv' => true,
                'test.user@test.np' => true,
                'test.user@test.nr' => true,
                'test.user@test.nra' => true,
                'test.user@test.nrw' => true,
                'test.user@test.ntt' => true,
                'test.user@test.nu' => true,
                'test.user@test.nyc' => true,
                'test.user@test.nz' => true,
                'test.user@test.obi' => true,
                'test.user@test.observer' => true,
                'test.user@test.off' => true,
                'test.user@test.office' => true,
                'test.user@test.okinawa' => true,
                'test.user@test.olayan' => true,
                'test.user@test.olayangroup' => true,
                'test.user@test.oldnavy' => true,
                'test.user@test.ollo' => true,
                'test.user@test.om' => true,
                'test.user@test.omega' => true,
                'test.user@test.one' => true,
                'test.user@test.ong' => true,
                'test.user@test.onl' => true,
                'test.user@test.online' => true,
                'test.user@test.onyourside' => true,
                'test.user@test.ooo' => true,
                'test.user@test.open' => true,
                'test.user@test.oracle' => true,
                'test.user@test.orange' => true,
                'test.user@test.org' => true,
                'test.user@test.organic' => true,
                'test.user@test.origins' => true,
                'test.user@test.osaka' => true,
                'test.user@test.otsuka' => true,
                'test.user@test.ott' => true,
                'test.user@test.ovh' => true,
                'test.user@test.pa' => true,
                'test.user@test.page' => true,
                'test.user@test.panasonic' => true,
                'test.user@test.panerai' => true,
                'test.user@test.paris' => true,
                'test.user@test.pars' => true,
                'test.user@test.partners' => true,
                'test.user@test.parts' => true,
                'test.user@test.party' => true,
                'test.user@test.passagens' => true,
                'test.user@test.pay' => true,
                'test.user@test.pccw' => true,
                'test.user@test.pe' => true,
                'test.user@test.pet' => true,
                'test.user@test.pf' => true,
                'test.user@test.pfizer' => true,
                'test.user@test.pg' => true,
                'test.user@test.ph' => true,
                'test.user@test.pharmacy' => true,
                'test.user@test.phd' => true,
                'test.user@test.philips' => true,
                'test.user@test.phone' => true,
                'test.user@test.photo' => true,
                'test.user@test.photography' => true,
                'test.user@test.photos' => true,
                'test.user@test.physio' => true,
                'test.user@test.piaget' => true,
                'test.user@test.pics' => true,
                'test.user@test.pictet' => true,
                'test.user@test.pictures' => true,
                'test.user@test.pid' => true,
                'test.user@test.pin' => true,
                'test.user@test.ping' => true,
                'test.user@test.pink' => true,
                'test.user@test.pioneer' => true,
                'test.user@test.pizza' => true,
                'test.user@test.pk' => true,
                'test.user@test.pl' => true,
                'test.user@test.place' => true,
                'test.user@test.play' => true,
                'test.user@test.playstation' => true,
                'test.user@test.plumbing' => true,
                'test.user@test.plus' => true,
                'test.user@test.pm' => true,
                'test.user@test.pn' => true,
                'test.user@test.pnc' => true,
                'test.user@test.pohl' => true,
                'test.user@test.poker' => true,
                'test.user@test.politie' => true,
                'test.user@test.porn' => true,
                'test.user@test.post' => true,
                'test.user@test.pr' => true,
                'test.user@test.pramerica' => true,
                'test.user@test.praxi' => true,
                'test.user@test.press' => true,
                'test.user@test.prime' => true,
                'test.user@test.pro' => true,
                'test.user@test.prod' => true,
                'test.user@test.productions' => true,
                'test.user@test.prof' => true,
                'test.user@test.progressive' => true,
                'test.user@test.promo' => true,
                'test.user@test.properties' => true,
                'test.user@test.property' => true,
                'test.user@test.protection' => true,
                'test.user@test.pru' => true,
                'test.user@test.prudential' => true,
                'test.user@test.ps' => true,
                'test.user@test.pt' => true,
                'test.user@test.pub' => true,
                'test.user@test.pw' => true,
                'test.user@test.pwc' => true,
                'test.user@test.py' => true,
                'test.user@test.qa' => true,
                'test.user@test.qpon' => true,
                'test.user@test.quebec' => true,
                'test.user@test.quest' => true,
                'test.user@test.qvc' => true,
                'test.user@test.racing' => true,
                'test.user@test.radio' => true,
                'test.user@test.raid' => true,
                'test.user@test.re' => true,
                'test.user@test.read' => true,
                'test.user@test.realestate' => true,
                'test.user@test.realtor' => true,
                'test.user@test.realty' => true,
                'test.user@test.recipes' => true,
                'test.user@test.red' => true,
                'test.user@test.redstone' => true,
                'test.user@test.redumbrella' => true,
                'test.user@test.rehab' => true,
                'test.user@test.reise' => true,
                'test.user@test.reisen' => true,
                'test.user@test.reit' => true,
                'test.user@test.reliance' => true,
                'test.user@test.ren' => true,
                'test.user@test.rent' => true,
                'test.user@test.rentals' => true,
                'test.user@test.repair' => true,
                'test.user@test.report' => true,
                'test.user@test.republican' => true,
                'test.user@test.rest' => true,
                'test.user@test.restaurant' => true,
                'test.user@test.review' => true,
                'test.user@test.reviews' => true,
                'test.user@test.rexroth' => true,
                'test.user@test.rich' => true,
                'test.user@test.richardli' => true,
                'test.user@test.ricoh' => true,
                'test.user@test.rightathome' => true,
                'test.user@test.ril' => true,
                'test.user@test.rio' => true,
                'test.user@test.rip' => true,
                'test.user@test.rmit' => true,
                'test.user@test.ro' => true,
                'test.user@test.rocher' => true,
                'test.user@test.rocks' => true,
                'test.user@test.rodeo' => true,
                'test.user@test.rogers' => true,
                'test.user@test.room' => true,
                'test.user@test.rs' => true,
                'test.user@test.rsvp' => true,
                'test.user@test.ru' => true,
                'test.user@test.rugby' => true,
                'test.user@test.ruhr' => true,
                'test.user@test.run' => true,
                'test.user@test.rw' => true,
                'test.user@test.rwe' => true,
                'test.user@test.ryukyu' => true,
                'test.user@test.sa' => true,
                'test.user@test.saarland' => true,
                'test.user@test.safe' => true,
                'test.user@test.safety' => true,
                'test.user@test.sakura' => true,
                'test.user@test.sale' => true,
                'test.user@test.salon' => true,
                'test.user@test.samsclub' => true,
                'test.user@test.samsung' => true,
                'test.user@test.sandvik' => true,
                'test.user@test.sandvikcoromant' => true,
                'test.user@test.sanofi' => true,
                'test.user@test.sap' => true,
                'test.user@test.sapo' => true,
                'test.user@test.sarl' => true,
                'test.user@test.sas' => true,
                'test.user@test.save' => true,
                'test.user@test.saxo' => true,
                'test.user@test.sb' => true,
                'test.user@test.sbi' => true,
                'test.user@test.sbs' => true,
                'test.user@test.sc' => true,
                'test.user@test.sca' => true,
                'test.user@test.scb' => true,
                'test.user@test.schaeffler' => true,
                'test.user@test.schmidt' => true,
                'test.user@test.scholarships' => true,
                'test.user@test.school' => true,
                'test.user@test.schule' => true,
                'test.user@test.schwarz' => true,
                'test.user@test.science' => true,
                'test.user@test.scjohnson' => true,
                'test.user@test.scor' => true,
                'test.user@test.scot' => true,
                'test.user@test.sd' => true,
                'test.user@test.se' => true,
                'test.user@test.search' => true,
                'test.user@test.seat' => true,
                'test.user@test.secure' => true,
                'test.user@test.security' => true,
                'test.user@test.seek' => true,
                'test.user@test.select' => true,
                'test.user@test.sener' => true,
                'test.user@test.services' => true,
                'test.user@test.ses' => true,
                'test.user@test.seven' => true,
                'test.user@test.sew' => true,
                'test.user@test.sex' => true,
                'test.user@test.sexy' => true,
                'test.user@test.sfr' => true,
                'test.user@test.sg' => true,
                'test.user@test.sh' => true,
                'test.user@test.shangrila' => true,
                'test.user@test.sharp' => true,
                'test.user@test.shaw' => true,
                'test.user@test.shell' => true,
                'test.user@test.shia' => true,
                'test.user@test.shiksha' => true,
                'test.user@test.shoes' => true,
                'test.user@test.shop' => true,
                'test.user@test.shopping' => true,
                'test.user@test.shouji' => true,
                'test.user@test.show' => true,
                'test.user@test.showtime' => true,
                'test.user@test.shriram' => true,
                'test.user@test.si' => true,
                'test.user@test.silk' => true,
                'test.user@test.sina' => true,
                'test.user@test.singles' => true,
                'test.user@test.site' => true,
                'test.user@test.sj' => true,
                'test.user@test.sk' => true,
                'test.user@test.ski' => true,
                'test.user@test.skin' => true,
                'test.user@test.sky' => true,
                'test.user@test.skype' => true,
                'test.user@test.sl' => true,
                'test.user@test.sling' => true,
                'test.user@test.sm' => true,
                'test.user@test.smart' => true,
                'test.user@test.smile' => true,
                'test.user@test.sn' => true,
                'test.user@test.sncf' => true,
                'test.user@test.so' => true,
                'test.user@test.soccer' => true,
                'test.user@test.social' => true,
                'test.user@test.softbank' => true,
                'test.user@test.software' => true,
                'test.user@test.sohu' => true,
                'test.user@test.solar' => true,
                'test.user@test.solutions' => true,
                'test.user@test.song' => true,
                'test.user@test.sony' => true,
                'test.user@test.soy' => true,
                'test.user@test.space' => true,
                'test.user@test.spiegel' => true,
                'test.user@test.sport' => true,
                'test.user@test.spot' => true,
                'test.user@test.spreadbetting' => true,
                'test.user@test.sr' => true,
                'test.user@test.srl' => true,
                'test.user@test.srt' => true,
                'test.user@test.st' => true,
                'test.user@test.stada' => true,
                'test.user@test.staples' => true,
                'test.user@test.star' => true,
                'test.user@test.starhub' => true,
                'test.user@test.statebank' => true,
                'test.user@test.statefarm' => true,
                'test.user@test.statoil' => true,
                'test.user@test.stc' => true,
                'test.user@test.stcgroup' => true,
                'test.user@test.stockholm' => true,
                'test.user@test.storage' => true,
                'test.user@test.store' => true,
                'test.user@test.stream' => true,
                'test.user@test.studio' => true,
                'test.user@test.study' => true,
                'test.user@test.style' => true,
                'test.user@test.su' => true,
                'test.user@test.sucks' => true,
                'test.user@test.supplies' => true,
                'test.user@test.supply' => true,
                'test.user@test.support' => true,
                'test.user@test.surf' => true,
                'test.user@test.surgery' => true,
                'test.user@test.suzuki' => true,
                'test.user@test.sv' => true,
                'test.user@test.swatch' => true,
                'test.user@test.swiftcover' => true,
                'test.user@test.swiss' => true,
                'test.user@test.sx' => true,
                'test.user@test.sy' => true,
                'test.user@test.sydney' => true,
                'test.user@test.symantec' => true,
                'test.user@test.systems' => true,
                'test.user@test.sz' => true,
                'test.user@test.tab' => true,
                'test.user@test.taipei' => true,
                'test.user@test.talk' => true,
                'test.user@test.taobao' => true,
                'test.user@test.target' => true,
                'test.user@test.tatamotors' => true,
                'test.user@test.tatar' => true,
                'test.user@test.tattoo' => true,
                'test.user@test.tax' => true,
                'test.user@test.taxi' => true,
                'test.user@test.tc' => true,
                'test.user@test.tci' => true,
                'test.user@test.td' => true,
                'test.user@test.tdk' => true,
                'test.user@test.team' => true,
                'test.user@test.tech' => true,
                'test.user@test.technology' => true,
                'test.user@test.tel' => true,
                'test.user@test.telecity' => true,
                'test.user@test.telefonica' => true,
                'test.user@test.temasek' => true,
                'test.user@test.tennis' => true,
                'test.user@test.teva' => true,
                'test.user@test.tf' => true,
                'test.user@test.tg' => true,
                'test.user@test.th' => true,
                'test.user@test.thd' => true,
                'test.user@test.theater' => true,
                'test.user@test.theatre' => true,
                'test.user@test.tiaa' => true,
                'test.user@test.tickets' => true,
                'test.user@test.tienda' => true,
                'test.user@test.tiffany' => true,
                'test.user@test.tips' => true,
                'test.user@test.tires' => true,
                'test.user@test.tirol' => true,
                'test.user@test.tj' => true,
                'test.user@test.tjmaxx' => true,
                'test.user@test.tjx' => true,
                'test.user@test.tk' => true,
                'test.user@test.tkmaxx' => true,
                'test.user@test.tl' => true,
                'test.user@test.tm' => true,
                'test.user@test.tmall' => true,
                'test.user@test.tn' => true,
                'test.user@test.to' => true,
                'test.user@test.today' => true,
                'test.user@test.tokyo' => true,
                'test.user@test.tools' => true,
                'test.user@test.top' => true,
                'test.user@test.toray' => true,
                'test.user@test.toshiba' => true,
                'test.user@test.total' => true,
                'test.user@test.tours' => true,
                'test.user@test.town' => true,
                'test.user@test.toyota' => true,
                'test.user@test.toys' => true,
                'test.user@test.tr' => true,
                'test.user@test.trade' => true,
                'test.user@test.trading' => true,
                'test.user@test.training' => true,
                'test.user@test.travel' => true,
                'test.user@test.travelchannel' => true,
                'test.user@test.travelers' => true,
                'test.user@test.travelersinsurance' => true,
                'test.user@test.trust' => true,
                'test.user@test.trv' => true,
                'test.user@test.tt' => true,
                'test.user@test.tube' => true,
                'test.user@test.tui' => true,
                'test.user@test.tunes' => true,
                'test.user@test.tushu' => true,
                'test.user@test.tv' => true,
                'test.user@test.tvs' => true,
                'test.user@test.tw' => true,
                'test.user@test.tz' => true,
                'test.user@test.ua' => true,
                'test.user@test.ubank' => true,
                'test.user@test.ubs' => true,
                'test.user@test.uconnect' => true,
                'test.user@test.ug' => true,
                'test.user@test.uk' => true,
                'test.user@test.unicom' => true,
                'test.user@test.university' => true,
                'test.user@test.uno' => true,
                'test.user@test.uol' => true,
                'test.user@test.ups' => true,
                'test.user@test.us' => true,
                'test.user@test.uy' => true,
                'test.user@test.uz' => true,
                'test.user@test.va' => true,
                'test.user@test.vacations' => true,
                'test.user@test.vana' => true,
                'test.user@test.vanguard' => true,
                'test.user@test.vc' => true,
                'test.user@test.ve' => true,
                'test.user@test.vegas' => true,
                'test.user@test.ventures' => true,
                'test.user@test.verisign' => true,
                'test.user@test.versicherung' => true,
                'test.user@test.vet' => true,
                'test.user@test.vg' => true,
                'test.user@test.vi' => true,
                'test.user@test.viajes' => true,
                'test.user@test.video' => true,
                'test.user@test.vig' => true,
                'test.user@test.viking' => true,
                'test.user@test.villas' => true,
                'test.user@test.vin' => true,
                'test.user@test.vip' => true,
                'test.user@test.virgin' => true,
                'test.user@test.visa' => true,
                'test.user@test.vision' => true,
                'test.user@test.vista' => true,
                'test.user@test.vistaprint' => true,
                'test.user@test.viva' => true,
                'test.user@test.vivo' => true,
                'test.user@test.vlaanderen' => true,
                'test.user@test.vn' => true,
                'test.user@test.vodka' => true,
                'test.user@test.volkswagen' => true,
                'test.user@test.volvo' => true,
                'test.user@test.vote' => true,
                'test.user@test.voting' => true,
                'test.user@test.voto' => true,
                'test.user@test.voyage' => true,
                'test.user@test.vu' => true,
                'test.user@test.vuelos' => true,
                'test.user@test.wales' => true,
                'test.user@test.walmart' => true,
                'test.user@test.walter' => true,
                'test.user@test.wang' => true,
                'test.user@test.wanggou' => true,
                'test.user@test.warman' => true,
                'test.user@test.watch' => true,
                'test.user@test.watches' => true,
                'test.user@test.weather' => true,
                'test.user@test.weatherchannel' => true,
                'test.user@test.webcam' => true,
                'test.user@test.weber' => true,
                'test.user@test.website' => true,
                'test.user@test.wed' => true,
                'test.user@test.wedding' => true,
                'test.user@test.weibo' => true,
                'test.user@test.weir' => true,
                'test.user@test.wf' => true,
                'test.user@test.whoswho' => true,
                'test.user@test.wien' => true,
                'test.user@test.wiki' => true,
                'test.user@test.williamhill' => true,
                'test.user@test.win' => true,
                'test.user@test.windows' => true,
                'test.user@test.wine' => true,
                'test.user@test.winners' => true,
                'test.user@test.wme' => true,
                'test.user@test.wolterskluwer' => true,
                'test.user@test.woodside' => true,
                'test.user@test.work' => true,
                'test.user@test.works' => true,
                'test.user@test.world' => true,
                'test.user@test.wow' => true,
                'test.user@test.ws' => true,
                'test.user@test.wtc' => true,
                'test.user@test.wtf' => true,
                'test.user@test.xbox' => true,
                'test.user@test.xerox' => true,
                'test.user@test.xfinity' => true,
                'test.user@test.xihuan' => true,
                'test.user@test.xin' => true,
                'test.user@test.xn--11b4c3d' => true,
                'test.user@test.xn--1ck2e1b' => true,
                'test.user@test.xn--1qqw23a' => true,
                'test.user@test.xn--2scrj9c' => true,
                'test.user@test.xn--30rr7y' => true,
                'test.user@test.xn--3bst00m' => true,
                'test.user@test.xn--3ds443g' => true,
                'test.user@test.xn--3e0b707e' => true,
                'test.user@test.xn--3hcrj9c' => true,
                'test.user@test.xn--3oq18vl8pn36a' => true,
                'test.user@test.xn--3pxu8k' => true,
                'test.user@test.xn--42c2d9a' => true,
                'test.user@test.xn--45br5cyl' => true,
                'test.user@test.xn--45brj9c' => true,
                'test.user@test.xn--45q11c' => true,
                'test.user@test.xn--4gbrim' => true,
                'test.user@test.xn--54b7fta0cc' => true,
                'test.user@test.xn--55qw42g' => true,
                'test.user@test.xn--55qx5d' => true,
                'test.user@test.xn--5su34j936bgsg' => true,
                'test.user@test.xn--5tzm5g' => true,
                'test.user@test.xn--6frz82g' => true,
                'test.user@test.xn--6qq986b3xl' => true,
                'test.user@test.xn--80adxhks' => true,
                'test.user@test.xn--80ao21a' => true,
                'test.user@test.xn--80aqecdr1a' => true,
                'test.user@test.xn--80asehdb' => true,
                'test.user@test.xn--80aswg' => true,
                'test.user@test.xn--8y0a063a' => true,
                'test.user@test.xn--90a3ac' => true,
                'test.user@test.xn--90ae' => true,
                'test.user@test.xn--90ais' => true,
                'test.user@test.xn--9dbq2a' => true,
                'test.user@test.xn--9et52u' => true,
                'test.user@test.xn--9krt00a' => true,
                'test.user@test.xn--b4w605ferd' => true,
                'test.user@test.xn--bck1b9a5dre4c' => true,
                'test.user@test.xn--c1avg' => true,
                'test.user@test.xn--c2br7g' => true,
                'test.user@test.xn--cck2b3b' => true,
                'test.user@test.xn--cg4bki' => true,
                'test.user@test.xn--clchc0ea0b2g2a9gcd' => true,
                'test.user@test.xn--czr694b' => true,
                'test.user@test.xn--czrs0t' => true,
                'test.user@test.xn--czru2d' => true,
                'test.user@test.xn--d1acj3b' => true,
                'test.user@test.xn--d1alf' => true,
                'test.user@test.xn--e1a4c' => true,
                'test.user@test.xn--eckvdtc9d' => true,
                'test.user@test.xn--efvy88h' => true,
                'test.user@test.xn--estv75g' => true,
                'test.user@test.xn--fct429k' => true,
                'test.user@test.xn--fhbei' => true,
                'test.user@test.xn--fiq228c5hs' => true,
                'test.user@test.xn--fiq64b' => true,
                'test.user@test.xn--fiqs8s' => true,
                'test.user@test.xn--fiqz9s' => true,
                'test.user@test.xn--fjq720a' => true,
                'test.user@test.xn--flw351e' => true,
                'test.user@test.xn--fpcrj9c3d' => true,
                'test.user@test.xn--fzc2c9e2c' => true,
                'test.user@test.xn--fzys8d69uvgm' => true,
                'test.user@test.xn--g2xx48c' => true,
                'test.user@test.xn--gckr3f0f' => true,
                'test.user@test.xn--gecrj9c' => true,
                'test.user@test.xn--gk3at1e' => true,
                'test.user@test.xn--h2breg3eve' => true,
                'test.user@test.xn--h2brj9c' => true,
                'test.user@test.xn--h2brj9c8c' => true,
                'test.user@test.xn--hxt814e' => true,
                'test.user@test.xn--i1b6b1a6a2e' => true,
                'test.user@test.xn--imr513n' => true,
                'test.user@test.xn--io0a7i' => true,
                'test.user@test.xn--j1aef' => true,
                'test.user@test.xn--j1amh' => true,
                'test.user@test.xn--j6w193g' => true,
                'test.user@test.xn--jlq61u9w7b' => true,
                'test.user@test.xn--jvr189m' => true,
                'test.user@test.xn--kcrx77d1x4a' => true,
                'test.user@test.xn--kprw13d' => true,
                'test.user@test.xn--kpry57d' => true,
                'test.user@test.xn--kpu716f' => true,
                'test.user@test.xn--kput3i' => true,
                'test.user@test.xn--l1acc' => true,
                'test.user@test.xn--lgbbat1ad8j' => true,
                'test.user@test.xn--mgb9awbf' => true,
                'test.user@test.xn--mgba3a3ejt' => true,
                'test.user@test.xn--mgba3a4f16a' => true,
                'test.user@test.xn--mgba7c0bbn0a' => true,
                'test.user@test.xn--mgbaakc7dvf' => true,
                'test.user@test.xn--mgbaam7a8h' => true,
                'test.user@test.xn--mgbab2bd' => true,
                'test.user@test.xn--mgbai9azgqp6j' => true,
                'test.user@test.xn--mgbayh7gpa' => true,
                'test.user@test.xn--mgbb9fbpob' => true,
                'test.user@test.xn--mgbbh1a' => true,
                'test.user@test.xn--mgbbh1a71e' => true,
                'test.user@test.xn--mgbc0a9azcg' => true,
                'test.user@test.xn--mgbca7dzdo' => true,
                'test.user@test.xn--mgberp4a5d4ar' => true,
                'test.user@test.xn--mgbgu82a' => true,
                'test.user@test.xn--mgbi4ecexp' => true,
                'test.user@test.xn--mgbpl2fh' => true,
                'test.user@test.xn--mgbt3dhd' => true,
                'test.user@test.xn--mgbtx2b' => true,
                'test.user@test.xn--mgbx4cd0ab' => true,
                'test.user@test.xn--mix891f' => true,
                'test.user@test.xn--mk1bu44c' => true,
                'test.user@test.xn--mxtq1m' => true,
                'test.user@test.xn--ngbc5azd' => true,
                'test.user@test.xn--ngbe9e0a' => true,
                'test.user@test.xn--ngbrx' => true,
                'test.user@test.xn--node' => true,
                'test.user@test.xn--nqv7f' => true,
                'test.user@test.xn--nqv7fs00ema' => true,
                'test.user@test.xn--nyqy26a' => true,
                'test.user@test.xn--o3cw4h' => true,
                'test.user@test.xn--ogbpf8fl' => true,
                'test.user@test.xn--otu796d' => true,
                'test.user@test.xn--p1acf' => true,
                'test.user@test.xn--p1ai' => true,
                'test.user@test.xn--pbt977c' => true,
                'test.user@test.xn--pgbs0dh' => true,
                'test.user@test.xn--pssy2u' => true,
                'test.user@test.xn--q9jyb4c' => true,
                'test.user@test.xn--qcka1pmc' => true,
                'test.user@test.xn--qxam' => true,
                'test.user@test.xn--rhqv96g' => true,
                'test.user@test.xn--rovu88b' => true,
                'test.user@test.xn--rvc1e0am3e' => true,
                'test.user@test.xn--s9brj9c' => true,
                'test.user@test.xn--ses554g' => true,
                'test.user@test.xn--t60b56a' => true,
                'test.user@test.xn--tckwe' => true,
                'test.user@test.xn--tiq49xqyj' => true,
                'test.user@test.xn--unup4y' => true,
                'test.user@test.xn--vermgensberater-ctb' => true,
                'test.user@test.xn--vermgensberatung-pwb' => true,
                'test.user@test.xn--vhquv' => true,
                'test.user@test.xn--vuq861b' => true,
                'test.user@test.xn--w4r85el8fhu5dnra' => true,
                'test.user@test.xn--w4rs40l' => true,
                'test.user@test.xn--wgbh1c' => true,
                'test.user@test.xn--wgbl6a' => true,
                'test.user@test.xn--xhq521b' => true,
                'test.user@test.xn--xkc2al3hye2a' => true,
                'test.user@test.xn--xkc2dl3a5ee0h' => true,
                'test.user@test.xn--y9a3aq' => true,
                'test.user@test.xn--yfro4i67o' => true,
                'test.user@test.xn--ygbi2ammx' => true,
                'test.user@test.xn--zfr164b' => true,
                'test.user@test.xperia' => true,
                'test.user@test.xxx' => true,
                'test.user@test.xyz' => true,
                'test.user@test.yachts' => true,
                'test.user@test.yahoo' => true,
                'test.user@test.yamaxun' => true,
                'test.user@test.yandex' => true,
                'test.user@test.ye' => true,
                'test.user@test.yodobashi' => true,
                'test.user@test.yoga' => true,
                'test.user@test.yokohama' => true,
                'test.user@test.you' => true,
                'test.user@test.youtube' => true,
                'test.user@test.yt' => true,
                'test.user@test.yun' => true,
                'test.user@test.za' => true,
                'test.user@test.zappos' => true,
                'test.user@test.zara' => true,
                'test.user@test.zero' => true,
                'test.user@test.zip' => true,
                'test.user@test.zippo' => true,
                'test.user@test.zm' => true,
                'test.user@test.zone' => true,
                'test.user@test.zuerich' => true,
                'test.user@test.zw' => true,
                'tester@intra.local' => true,
                'q@q.c;om' => false
        );
        
        foreach( $emailAddresses as $emailAddress => $result) {
            if ($result) {
                $this->assertTrue(check_email($emailAddress));
            }
            else {
                $this->assertFalse(check_email($emailAddress));
            }
        }
        
    }

    public function test_generate_password() {

        $pw = generate_password();
        $len = strlen($pw);
        $this->assertEquals(8, $len);
        
    }

    public function test_generatePassword() {

        $pw = generatePassword('y');
        $len = strlen($pw);
        $this->assertEquals(14, $len);
        
        $pw = generatePassword('n');
        $len = strlen($pw);
        $this->assertEquals(8, $len);
        
    }

    public function test_setter() {

        list($tAccessControlLevelDirs, $tAccessControlLevelFiles ) = setAccessControlLevel('dirs');
        $this->assertEquals('checked', $tAccessControlLevelDirs);
        $this->assertEquals('', $tAccessControlLevelFiles);
        list($tAccessControlLevelDirs, $tAccessControlLevelFiles ) = setAccessControlLevel('files');
        $this->assertEquals('', $tAccessControlLevelDirs);
        $this->assertEquals('checked', $tAccessControlLevelFiles);
        
        list($tPerRepoFilesYes, $tPerRepoFilesNo ) = setPerRepoFiles('YES');
        $this->assertEquals('checked', $tPerRepoFilesYes);
        $this->assertEquals('', $tPerRepoFilesNo);
        list($tPerRepoFilesYes, $tPerRepoFilesNo ) = setPerRepoFiles('NO');
        $this->assertEquals('checked', $tPerRepoFilesNo);
        $this->assertEquals('', $tPerRepoFilesYes);
        
        list($tPathSortOrderAsc, $tPathSortOrderDesc ) = setPathSortOrder('ASC');
        $this->assertEquals('checked', $tPathSortOrderAsc);
        $this->assertEquals('', $tPathSortOrderDesc);
        list($tPathSortOrderAsc, $tPathSortOrderDesc ) = setPathSortOrder('DESC');
        $this->assertEquals('checked', $tPathSortOrderDesc);
        $this->assertEquals('', $tPathSortOrderAsc);
        
        list($tLdapUserSortAsc, $tLdapUserSortDesc ) = setLdapUserSort('ASC');
        $this->assertEquals('checked', $tLdapUserSortAsc);
        $this->assertEquals('', $tLdapUserSortDesc);
        list($tLdapUserSortAsc, $tLdapUserSortDesc ) = setLdapUserSort('DESC');
        $this->assertEquals('checked', $tLdapUserSortDesc);
        $this->assertEquals('', $tLdapUserSortAsc);
        
        list($tLdapBindUseLoginDataYes, $tLdapBindUseLoginDataNo ) = setLdapBindUseLoginData(1);
        $this->assertEquals('checked', $tLdapBindUseLoginDataYes);
        $this->assertEquals('', $tLdapBindUseLoginDataNo);
        list($tLdapBindUseLoginDataYes, $tLdapBindUseLoginDataNo ) = setLdapBindUseLoginData(0);
        $this->assertEquals('checked', $tLdapBindUseLoginDataNo);
        $this->assertEquals('', $tLdapBindUseLoginDataYes);
        
        list($tAnonAccessYes, $tAnonAccessNo ) = setAnonAccess(1);
        $this->assertEquals('checked', $tAnonAccessYes);
        $this->assertEquals('', $tAnonAccessNo);
        list($tAnonAccessYes, $tAnonAccessNo ) = setAnonAccess(0);
        $this->assertEquals('checked', $tAnonAccessNo);
        $this->assertEquals('', $tAnonAccessYes);
        
        list($tViewvcConfigYes, $tViewvcConfigNo ) = setViewvcConfig('YES');
        $this->assertEquals('checked', $tViewvcConfigYes);
        $this->assertEquals('', $tViewvcConfigNo);
        list($tViewvcConfigYes, $tViewvcConfigNo ) = setViewvcConfig('NO');
        $this->assertEquals('checked', $tViewvcConfigNo);
        $this->assertEquals('', $tViewvcConfigYes);
        
        list($tJavaScriptYes, $tJavaScriptNo ) = setJavaScript('YES');
        $this->assertEquals('checked', $tJavaScriptYes);
        $this->assertEquals('', $tJavaScriptNo);
        list($tJavaScriptYes, $tJavaScriptNo ) = setJavaScript('NO');
        $this->assertEquals('checked', $tJavaScriptNo);
        $this->assertEquals('', $tJavaScriptYes);
        
        list($tLoggingYes, $tLoggingNo ) = setLogging('YES');
        $this->assertEquals('checked', $tLoggingYes);
        $this->assertEquals('', $tLoggingNo);
        list($tLoggingYes, $tLoggingNo ) = setLogging('NO');
        $this->assertEquals('checked', $tLoggingNo);
        $this->assertEquals('', $tLoggingYes);
        
        list($tExpirePasswordYes, $tExpirePasswordNo ) = setPasswordExpires(1);
        $this->assertEquals('checked', $tExpirePasswordYes);
        $this->assertEquals('', $tExpirePasswordNo);
        list($tExpirePasswordYes, $tExpirePasswordNo ) = setPasswordExpires(0);
        $this->assertEquals('checked', $tExpirePasswordNo);
        $this->assertEquals('', $tExpirePasswordYes);
        
        list($tUserDefaultAccessRead, $tUserDefaultAccessWrite ) = setUserDefaultAccess('read');
        $this->assertEquals('checked', $tUserDefaultAccessRead);
        $this->assertEquals('', $tUserDefaultAccessWrite);
        list($tUserDefaultAccessRead, $tUserDefaultAccessWrite ) = setUserDefaultAccess('write');
        $this->assertEquals('checked', $tUserDefaultAccessWrite);
        $this->assertEquals('', $tUserDefaultAccessRead);
        
        list($tUseAuthUserFileYes, $tUseAuthUserFileNo ) = setUseAuthUserFile('YES');
        $this->assertEquals('checked', $tUseAuthUserFileYes);
        $this->assertEquals('', $tUseAuthUserFileNo);
        list($tUseAuthUserFileYes, $tUseAuthUserFileNo ) = setUseAuthUserFile('NO');
        $this->assertEquals('checked', $tUseAuthUserFileNo);
        $this->assertEquals('', $tUseAuthUserFileYes);
        
        list($tUseSvnAccessFileYes, $tUseSvnAccessFileNo ) = setUseSvnAccessFile('YES');
        $this->assertEquals('checked', $tUseSvnAccessFileYes);
        $this->assertEquals('', $tUseSvnAccessFileNo);
        list($tUseSvnAccessFileYes, $tUseSvnAccessFileNo ) = setUseSvnAccessFile('NO');
        $this->assertEquals('checked', $tUseSvnAccessFileNo);
        $this->assertEquals('', $tUseSvnAccessFileYes);
        
        list($tUseLdapYes, $tUseLdapNo ) = setUseLdap('YES');
        $this->assertEquals('checked', $tUseLdapYes);
        $this->assertEquals('', $tUseLdapNo);
        list($tUseLdapYes, $tUseLdapNo ) = setUseLdap('NO');
        $this->assertEquals('checked', $tUseLdapNo);
        $this->assertEquals('', $tUseLdapYes);
        
        list($tLdap2, $tLdap3 ) = setLdapprotocol(2);
        $this->assertEquals('checked', $tLdap2);
        $this->assertEquals('', $tLdap3);
        list($tLdap2, $tLdap3 ) = setLdapprotocol(3);
        $this->assertEquals('checked', $tLdap3);
        $this->assertEquals('', $tLdap2);
        
        list($tSessionInDatabaseYes, $tSessionInDatabaseNo ) = setSessionIndatabase('YES');
        $this->assertEquals('checked', $tSessionInDatabaseYes);
        $this->assertEquals('', $tSessionInDatabaseNo);
        
        list($tSessionInDatabaseYes, $tSessionInDatabaseNo ) = setSessionIndatabase('NO');
        $this->assertEquals('', $tSessionInDatabaseYes);
        $this->assertEquals('checked', $tSessionInDatabaseNo);
        
        list($tDropDatabaseTablesYes, $tDropDatabaseTablesNo ) = setDropDatabaseTables('YES');
        $this->assertEquals('checked', $tDropDatabaseTablesYes);
        $this->assertEquals('', $tDropDatabaseTablesNo);
        list($tDropDatabaseTablesYes, $tDropDatabaseTablesNo ) = setDropDatabaseTables('NO');
        $this->assertEquals('checked', $tDropDatabaseTablesNo);
        $this->assertEquals('', $tDropDatabaseTablesYes);
        
        list($tCreateDatabaseTablesYes, $tCreateDatabaseTablesNo ) = setCreateDatabaseTables('YES');
        $this->assertEquals('checked', $tCreateDatabaseTablesYes);
        $this->assertEquals('', $tCreateDatabaseTablesNo);
        list($tCreateDatabaseTablesYes, $tCreateDatabaseTablesNo ) = setCreateDatabaseTables('NO');
        $this->assertEquals('checked', $tCreateDatabaseTablesNo);
        $this->assertEquals('', $tCreateDatabaseTablesYes);
        
        list($tDatabaseCharsetDefault, $tDatabaseCollationDefault ) = setDatabaseCharset('mysql');
        $this->assertEquals('latin1', $tDatabaseCharsetDefault);
        $this->assertEquals('latin1_german1_ci', $tDatabaseCollationDefault);
        list($tDatabaseCharsetDefault, $tDatabaseCollationDefault ) = setDatabaseCharset('mysqli');
        $this->assertEquals('latin1', $tDatabaseCharsetDefault);
        $this->assertEquals('latin1_german1_ci', $tDatabaseCollationDefault);
        list($tDatabaseCharsetDefault, $tDatabaseCollationDefault ) = setDatabaseCharset('oci8');
        $this->assertEquals('', $tDatabaseCharsetDefault);
        $this->assertEquals('', $tDatabaseCollationDefault);
        
        list($tPwSha, $tPwApacheMd5, $tPwMd5, $tPwCrypt, $tPwType ) = setEncryption('sha');
        $this->assertEquals('checked', $tPwSha);
        $this->assertEquals('', $tPwApacheMd5);
        $this->assertEquals('', $tPwMd5);
        $this->assertEquals('', $tPwCrypt);
        $this->assertEquals('sha', $tPwType);
        
        list($tPwSha, $tPwApacheMd5, $tPwMd5, $tPwCrypt, $tPwType ) = setEncryption('apr-md5');
        $this->assertEquals('', $tPwSha);
        $this->assertEquals('checked', $tPwApacheMd5);
        $this->assertEquals('', $tPwMd5);
        $this->assertEquals('', $tPwCrypt);
        $this->assertEquals('apr-md5', $tPwType);
        
        list($tPwSha, $tPwApacheMd5, $tPwMd5, $tPwCrypt, $tPwType ) = setEncryption('md5');
        $this->assertEquals('', $tPwSha);
        $this->assertEquals('', $tPwApacheMd5);
        $this->assertEquals('checked', $tPwMd5);
        $this->assertEquals('', $tPwCrypt);
        $this->assertEquals('md5', $tPwType);
        
        list($tPwSha, $tPwApacheMd5, $tPwMd5, $tPwCrypt, $tPwType ) = setEncryption('');
        $this->assertEquals('', $tPwSha);
        $this->assertEquals('', $tPwApacheMd5);
        $this->assertEquals('', $tPwMd5);
        $this->assertEquals('checked', $tPwCrypt);
        $this->assertEquals('crypt', $tPwType);
        
        list($tDatabaseMySQL, $tDatabaseMySQLi, $tDatabasePostgreSQL, $tDatabaseOracle ) = setDatabaseValues('mysql');
        $this->assertEquals('checked', $tDatabaseMySQL);
        $this->assertEquals('', $tDatabaseMySQLi);
        $this->assertEquals('', $tDatabasePostgreSQL);
        $this->assertEquals('', $tDatabaseOracle);
        
        list($tDatabaseMySQL, $tDatabaseMySQLi, $tDatabasePostgreSQL, $tDatabaseOracle ) = setDatabaseValues('mysqli');
        $this->assertEquals('', $tDatabaseMySQL);
        $this->assertEquals('checked', $tDatabaseMySQLi);
        $this->assertEquals('', $tDatabasePostgreSQL);
        $this->assertEquals('', $tDatabaseOracle);
        
        list($tDatabaseMySQL, $tDatabaseMySQLi, $tDatabasePostgreSQL, $tDatabaseOracle ) = setDatabaseValues('postgres8');
        $this->assertEquals('', $tDatabaseMySQL);
        $this->assertEquals('', $tDatabaseMySQLi);
        $this->assertEquals('checked', $tDatabasePostgreSQL);
        $this->assertEquals('', $tDatabaseOracle);
        
        list($tDatabaseMySQL, $tDatabaseMySQLi, $tDatabasePostgreSQL, $tDatabaseOracle ) = setDatabaseValues('oci8');
        $this->assertEquals('', $tDatabaseMySQL);
        $this->assertEquals('', $tDatabaseMySQLi);
        $this->assertEquals('', $tDatabasePostgreSQL);
        $this->assertEquals('checked', $tDatabaseOracle);
        
        list($tDatabaseMySQL, $tDatabaseMySQLi, $tDatabasePostgreSQL, $tDatabaseOracle ) = setDatabaseValues('');
        $this->assertEquals('', $tDatabaseMySQL);
        $this->assertEquals('', $tDatabaseMySQLi);
        $this->assertEquals('', $tDatabasePostgreSQL);
        $this->assertEquals('', $tDatabaseOracle);
        
    }

    public function test_getter() {

        $value = getLoggingFromSession();
        $this->assertEquals('YES', $value);
        
        $value = getJavaScriptFromSession();
        $this->assertEquals('YES', $value);
        
        $value = getPageSizeFromSession();
        $this->assertEquals(30, $value);
        
        $value = getMinAdminPwSizeFromSession();
        $this->assertEquals(14, $value);
        
        $value = getMinUserPwSizeFromSession();
        $this->assertEquals(8, $value);
        
        $value = getExpirePasswordFromSession();
        $this->assertEquals(1, $value);
        
        $value = getPwEncFromSession();
        $this->assertEquals('md5', $value);
        
        $value = getUserDefaultAccessFromSession();
        $this->assertEquals('read', $value);
        
        $value = getCustom1FromSession();
        $this->assertEquals('', $value);
        
        $value = getCustom2FromSession();
        $this->assertEquals('', $value);
        
        $value = getCustom3FromSession();
        $this->assertEquals('', $value);
        
        $value = getAuthUserFileFromSession();
        $this->assertEquals('', $value);
        
        $value = getSvnAccessFileFromSession();
        $this->assertEquals('', $value);
        
        $_SESSION = array();
        $_SESSION[SVN_INST]['logging'] = 'NO';
        $_SESSION[SVN_INST]['javaScript'] = 'NO';
        $_SESSION[SVN_INST]['pageSize'] = 40;
        $_SESSION[SVN_INST]['minAdminPwSize'] = 10;
        $_SESSION[SVN_INST]['minUserPwSize'] = 10;
        $_SESSION[SVN_INST]['expirePassword'] = 0;
        $_SESSION[SVN_INST]['pwEnc'] = 'crypt';
        $_SESSION[SVN_INST]['userDefaultAccess'] = 'write';
        $_SESSION[SVN_INST]['custom1'] = 'custom1';
        $_SESSION[SVN_INST]['custom2'] = 'custom2';
        $_SESSION[SVN_INST]['custom3'] = 'custom3';
        $_SESSION[SVN_INST]['authUserFile'] = '/etc/svn/svnpasswd';
        $_SESSION[SVN_INST]['svnAccessFile'] = '/etc/svn/svnaccess';
        
        $value = getLoggingFromSession();
        $this->assertEquals('NO', $value);
        
        $value = getJavaScriptFromSession();
        $this->assertEquals('NO', $value);
        
        $value = getPageSizeFromSession();
        $this->assertEquals(40, $value);
        
        $value = getMinAdminPwSizeFromSession();
        $this->assertEquals(10, $value);
        
        $value = getMinUserPwSizeFromSession();
        $this->assertEquals(10, $value);
        
        $value = getExpirePasswordFromSession();
        $this->assertEquals(0, $value);
        
        $value = getPwEncFromSession();
        $this->assertEquals('crypt', $value);
        
        $value = getUserDefaultAccessFromSession();
        $this->assertEquals('write', $value);
        
        $value = getCustom1FromSession();
        $this->assertEquals('custom1', $value);
        
        $value = getCustom2FromSession();
        $this->assertEquals('custom2', $value);
        
        $value = getCustom3FromSession();
        $this->assertEquals('custom3', $value);
        
        $value = getAuthUserFileFromSession();
        $this->assertEquals('/etc/svn/svnpasswd', $value);
        
        $value = getSvnAccessFileFromSession();
        $this->assertEquals('/etc/svn/svnaccess', $value);
        
    }

    public function test_runIncli() {

        $this->assertTrue(runInCli());
        
    }

    public function test_sortLdapUsers() {

        $a = array(
                'sn' => 'Bertram',
                'givenname' => 'Paul'
        );
        $b = array(
                'sn' => 'Adam',
                'givenname' => 'Michael'
        );
        
        $this->assertTrue(sortLdapUsers($a, $b));
        
    }

    public function test_pacrypt() {

        $pw = pacrypt('Start!12345678', '$1$NLvGX4d3$SdtwVFvV8As0z8I0xtE8L.');
        $this->assertEquals('$1$NLvGX4d3$', $pw);
        
    }

    public function test_getGrepCommand() {

        $grep = getGrepCommand('');
        $this->assertStringEndsWith('grep', $grep);
        
    }

    public function test_getSvnadminCommand() {

        $svnadmin = getSvnAdminCommand('');
        $this->assertStringEndsWith('svnadmin', $svnadmin);
        
    }

    public function test_getApacheReloadCommand() {

        $reload = getApacheReloadCommand('');
        $this->assertEquals('', $reload);
        
    }

    public function test_translateRight() {

        $right = translateRight('read');
        $this->assertEquals('r', $right);
        
        $right = translateRight('write');
        $this->assertEquals('rw', $right);
        
        $right = translateRight('blabla');
        $this->assertEquals('', $right);
        
    }

    public function test_encode_subject() {

        $subject = encode_subject("Lost password reset", "iso-8859-1");
        $this->assertEquals("=?iso-8859-1?B?TG9zdCBwYXNzd29yZCByZXNldA==?=", $subject);
        
        $subject = encode_subject("Vergessenes Passwort zurücksetzen", "iso-8859-1");
        $this->assertEquals("=?iso-8859-1?B?VmVyZ2Vzc2VuZXMgUGFzc3dvcnQgenVyw7xja3NldHplbg==?=", $subject);
        
    }
    
    public function test_create_verify_string() {
        
        $str = create_verify_string();
        $this->assertEquals(32, strlen($str));
    }
    
}

?>