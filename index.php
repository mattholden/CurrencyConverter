<!--
Simple currency converter between gaming, fantasy, and sci-fi settings.
Thrown together in a few hours, when I grew tired of waiting on our GM to calculate
the price of items by hand and then translate it into GURPS "dollars" to get
a sense of our buying power. [I still love ya, Colin. :)]

All of the currencies, settings, and exchange rate formulas are owned by their respective
owners, none of which are me. This is provided purely for informational use, for folks who
are playing games set in these worlds and need to know the approximate value of their
money and their stuffs. If any trademark owner has a problem with their settings' currency
being here, let me know and I'll remove it. Please don't sue me. I don't have any money
anyway.

This code is made available under the MIT license. If you want to use it,
go right ahead. Would be cool if you dropped me a line to let me know.
Cheers!

Matt Holden (matt@mattholden.com)


Version: 	1.01
Date:		1/20/2013

CHANGELOG:
------------------------------------------------------------------------------------------------------
1.01		Added Knuts/Sickles/Galleons, Munny, Gil, Rupees, Septims, and Pokédollars.
1/20/2013	Added license, changelog, and todo/wontdo comments
			First Github commit

------------------------------------------------------------------------------------------------------
FOR CONSIDERATION IN FUTURE VERSIONS

* Consider implementing a living conversion rate for gold-based currencies by hitting an API or scraping
the current price from a site such as http://www.monex.com/liveprices

* For currencies whose rates were based on comparative buying power (ie, "This item costs X in USD and Y in Currency Foo"),
consider allowing a currency object to link to a sample Amazon item and tracking its price, so we can adjust the "buying power"
to reflect the current price of said item.

------------------------------------------------------------------------------------------------------
NEED MORE INFO TO IMPLEMENT
--------------------------------------------------------------------------------
Discworld:
http://discworld.starturtle.net/lpc/playing/documentation.c?path=/concepts/currency
Lots of currency options here, but haven't found a conversion to USD yet.
--------------------------------------------------------------------------------
Eragon:
http://shurtugal.com/interviews/monthly-qas-with-christopher-paolini/qa-with-christopher-paolini-august-2009/
Paolini states that he has never considered a value of crowns in USD, but they are made of gold, so "they would be worth more here".
So if we can find the gold amount by weight in a crown, we have a conversion.

http://inheritance.wikia.com/wiki/Crown - Sample purchases using crowns (for reference?)


--------------------------------------------------------------------------------
WON'T DO
--------------------------------------------------------------------------------
Sims:
http://sims.wikia.com/wiki/Simoleon

"Its value can vary between titles, and doesn't consistently reflect any real-life currency
(such as the US dollar or British pound), as many of the more expensive items are marked down in price,
while some of the cheapest may be marked up for example, in The Sims 2 an SUV costs approx. §4000,
while a pizza costs §40. This is the result of lower time that a Sim has to earn money in the series compared
to a real life person; usually items of daily duration are more expensive, while durable objects or house
structures are cheaper."
-------------------------------------------------------------------------------
Won't do any setting with "gold pieces" as a currency. There's just too many, and the pricing seems to be pretty
analagous with all of them to D&D gold pieces. All of them will be "some weight of gold in a coin, therefore..."
I made an exception for the Septim, because it actually has a name outside of just "gold", because it has a very easy conversion,
and because, frankly, I'm addicted to Skyrim at the moment.

Settings omitted off the top of my head for this reason, mainly listed so I don't look them up again later, include:

* Dragon Age
* Dragon Warrior
* Fable
* Lufia
* Summoner
* The Sword of Truth
* Ultima


-->

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head><title>Gaming Currency Converter</title>
<script><!--

/** Define all the currencies we will know how to work with */
var currencies = [

	{ "id":"1","name":"US Dollar", "value_usd":1, "source":"http://en.wikipedia.org/wiki/United_States_dollar", "optimizer":null },
	{ "id":"2","name":"GURPS Dollar", "value_usd":1, "source":"http://www.warehouse23.com/item.html?id=SJG01-0004", "optimizer":null },

	// The exchange rate in the source was based on the present-day price per ounce of gold; I updated to reflect more modern numbers
	{ "id":"3","name":"D&D Copper Piece", "value_usd":0.1, "source":"http://forums.sjgames.com/showthread.php?t=71850",
		"optimizer":function() {
		var left = to_optimize;
			to_optimize = 0;
			var response = "";

			// http://www.dandwiki.com/wiki/SRD3e:Money
			if (left >= 1000) {
				var plat = ~~(left / 1000);
				left = left - (plat * 1000);
				if (plat > 0) response = plat + " platinum pieces<br>";
			}
			if (left >= 100) {
				var gold = ~~(left / 100);
				left = left - (gold * 100);
				if (gold > 0) response = response + gold + " gold pieces<br>";
			}
			if (left >= 10) {
				var silver = ~~(left / 10);
				left = left - (silver * 10);
				if (silver > 0) response = response + silver + " silver pieces<br>";
			}

			if (left > 0) response = response + left + " copper pieces<br>";
			return response;

		}
	},

	{ "id":"4","name":"Gold-Pressed Latinum Slip", "setting":"Star Trek", "value_usd":1.00, "source":"http://www.coldnorth.com/owen/game/startrek/universe/source/money.htm",

		"optimizer":function() {

			var left = to_optimize;
			to_optimize = 0;
			var response = "";

			// disregard bricks, ingots for now as there is no "official" conversion rate
			if (left >= 2000) {
				var bars = ~~(left / 2000);
				left = left - (bars * 2000);
				if (bars > 0) response = bars + " bars<br>";
			}
			if (left >= 100) {
				var strips = ~~(left / 100);
				left = left - (strips * 100);
				if (strips > 0) response = response + strips + " strips<br>";
			}
			if (left > 0) response = response + left + " slips<br>";
			return response;
		}
	},

	{ "id":"5","name":"Federation Credits","setting":"Star Trek", "value_usd":0.10, "source":"http://www.coldnorth.com/owen/game/startrek/universe/source/money.htm","optimizer":null },
	{ "id":"6","name":"Bajoran Lita","setting":"Star Trek","value_usd":0.15,"source":"http://www.coldnorth.com/owen/game/startrek/universe/source/money.htm","optimizer":null },
	{ "id":"7","name":"Breen Mitondrium", "setting":"Star Trek", "value_usd":1.3,"source":"http://www.coldnorth.com/owen/game/startrek/universe/source/money.htm","optimizer":null },
	{ "id":"8","name":"Cardassian Lek", "setting":"Star Trek", "value_usd":0.17,"source":"http://www.coldnorth.com/owen/game/startrek/universe/source/money.htm","optimizer":null },
	{ "id":"9","name":"Dominion Lateral", "setting":"Star Trek", "value_usd":1.2, "source":"http://www.coldnorth.com/owen/game/startrek/universe/source/money.htm", "optimizer":null },
	{ "id":"10","name":"Gorn Tokbar", "setting":"Star Trek", "value_usd":4, "source":"http://www.coldnorth.com/owen/game/startrek/universe/source/money.htm", "optimizer":null },
	{ "id":"11","name":"Klingon Darsek", "setting":"Star Trek", "value_usd":0.75, "source":"http://www.coldnorth.com/owen/game/startrek/universe/source/money.htm", "optimizer":null },
	{ "id":"12","name":"Nausicaan Chiv'vig", "setting":"Star Trek", "value_usd":1.8, "source":"http://www.coldnorth.com/owen/game/startrek/universe/source/money.htm", "optimizer":null },
	{ "id":"13","name":"Romulan T'chak", "setting":"Star Trek", "value_usd":1.7, "source":"http://www.coldnorth.com/owen/game/startrek/universe/source/money.htm", "optimizer":null },
	{ "id":"14","name":"Tholian Doleen", "setting":"Star Trek", "value_usd":1.5, "source":"http://www.coldnorth.com/owen/game/startrek/universe/source/money.htm", "optimizer":null },


	{ "id":"15","name":"Solari","setting":"Dune", "value_usd":1.59, "source":"http://io9.com/354128/a-handy-currency-converter-for-alien-money", "optimizer":null },

	// source gave values based on old exchange rates; I updated to current ones
	{ "id":"16","name":"Dollarpound","setting":"Red Dwarf", "value_usd":0.3, "source":"http://io9.com/354128/a-handy-currency-converter-for-alien-money", "optimizer":null },

	{ "id":"17","name":"Galactic Standard Credit","setting":"Star Wars", "value_usd":0.5, "source":"http://io9.com/354128/a-handy-currency-converter-for-alien-money", "optimizer":null },

	{ "id":"18","name":"Knuts", "setting":"Harry Potter","value_usd":0.02,"source":"http://harrypotter.wikia.com/wiki/Wizarding_currency",
	"optimizer":function() {
		var left = to_optimize;
		to_optimize = 0;
		var response = "";
		if (left >= 493) {
			var galleons = ~~(left/493);
			left = left - (galleons*493);
			if (galleons > 0) response = galleons + " Galleons<br>";
		}
		if (left >= 17) {
			var sickles = ~~(left/17);
			left = left - (sickles*17);
			if (sickles > 0) response = response + sickles + " Sickles<br>";
		}
		if (left > 0) response = response + left + " Knuts<br>";
		return response;
		}
	},

	{ "id":"19", "name":"Gil",		"setting":"Final Fantasy","value_usd":0.05,"source":"http://home.eyesonff.com/archive/t-59353.html","optimizer":null },
	{ "id":"20", "name":"Munny",	"setting":"Kingdom Hearts","value_usd":2,"source":"http://worlddestiny.proboards.com/index.cgi?board=theories&action=display&thread=11084","optimizer":null },
	{ "id":"21", "name":"Septim",	"setting":"The Elder Scrolls","value_usd":1,"source":"http://www.gamefaqs.com/boards/615804-the-elder-scrolls-v-skyrim/62548495?page=1", "optimizer":null },
	{ "id":"22", "name":"Rupees",	"setting":"The Legend of Zelda","value_usd":0.04,"source":"http://zelda.wikia.com/wiki/Forum:Rupee_conversion_rate","optimizer":null },

	// source says pokedollar is analogous to yen. The exchange rate for yen happened to be nice and easy today when I compared...
	{ "id":"23","name":"Pokédollars","setting":"Pokémon","value_usd":0.01,"source":"http://www.gamefaqs.com/ds/989552-pokemon-black-version/answers?qid=259612","optimizer":null },

	];


var optimized = "";
var to_optimize = 0;
var result = "";
var didFrom = null;
var didTo = null;
var amount = 0;

/** Look up a currency type by its name */
function get(type) {
	for (var i = 0; i < currencies.length; i++) {
		if (currencies[i].id == type) {
			return currencies[i];
		}
	}
	return null;
}


function convert() {
	var from = get(document.getElementById("select_from").value);
	var to = get(document.getElementById("select_to").value);
	document.getElementById("from_value").value = from.value_usd;
	document.getElementById("to_value").value = to.value_usd;
	document.getElementById("from_source").value = from.source;
	document.getElementById("to_source").value = to.source;
	document.getElementById("from_name").value = from.name;
	document.getElementById("to_name").value = to.name;
	document.getElementById("to_id").value = to.id;
	document.getElementById("from_id").value = from.id;
	document.forms["converter"].submit();
}


-->
</script>

<?php

if ($_REQUEST["from_name"] != null && $_REQUEST["to_name"]  != null && $_REQUEST["amount"] != null && $_REQUEST["amount"] != 0) {

	$done = $_REQUEST["amount"] * ($_REQUEST["from_value"] / $_REQUEST["to_value"]);
	$result = $_REQUEST["amount"]." ".$_REQUEST["from_name"]." = ".$done." ".$_REQUEST["to_name"];

	echo("<script><!--\n");
	echo("result = \"".$result."\";\n");
	echo("to_optimize = ".$done.";\n");
	echo("var fOptimizer = get(\"".$_REQUEST["to_id"]."\").optimizer;\n");
	echo("if (fOptimizer != null) { optimized = \"Optimized denominations:<br>\" + fOptimizer(); }\n");
	echo("didFrom = get(\"".$_REQUEST["from_id"]."\");\n");
	echo("didTo = get(\"".$_REQUEST["to_id"]."\");\n");
	echo("amount = ".$_REQUEST["amount"].";\n");
	echo("-->\n</script>\n");
}

?>


</head>
<body><center>
<form name="converter" method="post" action="index.php">
<table width="75%">
<tr>
<td valign="top" height=65>Convert from:<br><select id="select_from" name="select_from">
<script><!--
    var setting = "";

    for (var j = 0; j < currencies.length; j++) {
    	setting = "";
    	if (currencies[j].setting != null) setting = " (" + currencies[j].setting + ")";
		document.write("<option value='" + currencies[j].id + "'" + ((didFrom != null && didFrom.name == currencies[j].name) ? " SELECTED" : "") + ">" + currencies[j].name + setting + "</option>");
    }

-->
</script>
</select></td>
<input type="hidden" name="from_value" id="from_value" />
<input type="hidden" name="from_source" id="from_source" />
<input type="hidden" name="from_name" id="from_name" />
<input type="hidden" name="from_id" id="from_id" />

<td valign="top" height=65>
<div id="draw_from_rate" width="100%">
<script><!--
  if (didFrom != null)  {
  	document.write("1 " + didFrom.name + " = " + didFrom.value_usd + " USD<br>");
  	document.write("Source: <a href='" + didFrom.source + "'>" + didFrom.source + "</a>");
  }
--></script>
</div>
<br>
</td>
</tr>
<tr>
<td valign="top" height=65>Convert to:<br><select id="select_to" name="select_to">
<script><!--
   for (var k = 0; k < currencies.length; k++) {
		setting = "";
    	if (currencies[k].setting != null) setting = " (" + currencies[k].setting + ")";

    	document.write("<option value='" + currencies[k].id + "'" + ((didTo != null && didTo.name == currencies[k].name) ? " SELECTED" : "") + ">" + currencies[k].name + setting + "</option>");
   }
-->
</script>
</select></td>
<input type="hidden" name="to_value" id="to_value" />
<input type="hidden" name="to_source" id="to_source" />
<input type="hidden" name="to_name" id="to_name" />
<input type="hidden" name="to_id" id="to_id" />


<td valign="top" height=65>
<div id="draw_to_rate" width="100%">
<script><!--
  if (didTo != null) {
  	document.write("1 " + didTo.name + " = " + didTo.value_usd + " USD<br>");
  	document.write("Source: <a href='" + didTo.source + "'>" + didTo.source + "</a>");
  }
--></script>
</div>
<br>
</td>
</tr>
<tr>
<td valign="top" height=65>Amount: <input type="text" id="amount" name="amount">
<script><!--
  if (amount != 0) {
  	document.getElementById("amount").value = amount;
  }
-->
</script>

<input type="button" value="Convert" onClick="convert();" /></td>
<td valign="top" height=65>
<div width=100% id="result">
<script><!--
  document.write(result);
--></script></div>
<br><div width=100% id="optimized">
<script><!--
  document.write(optimized);
--></script></div>

</td>
</tr>
</table>
</form>
<br><br>
</center>
</body>
</html>





