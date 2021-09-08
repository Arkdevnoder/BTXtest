# BTXtest

Example PHP injection:

<pre>
$APPLICATION->IncludeComponent(
	"legacy:test", 
	"test",
	array(
		"IB_ID" => "4",
		"FIELD_SALARY" => "UF_SALARY",
		"MANAGED_CACHE" => true,
		"IS_CACHE" => true,
		"TIME_CACHE" => "10",
		"HASH_CACHE" => __FILE__,
		"TEMPLATE" => ""
	),
	false
);
</pre>
