<?php

/**
 * Customize TAX ID field or placeholder in WHMCS Client and Contact forms
 *
 * @copyright Copyright (c) 2007 DotRoll Kft. (http://www.dotroll.com)
 * @author Zoltán Istvanovszki <zoltan.istvanovszki@dotroll.com>
 * @since 2019-05-15
 * @package dotroll-whmcs-tools
 * @license https://www.gnu.org/licenses/gpl.txt GNU General Public License, version 3
 */
\add_hook('ClientAreaHeadOutput', 1, function(array $vars): string {
	$retval = '';
	if (
		!empty($vars['templatefile']) &&
		(\in_array($vars['templatefile'], ['clientregister', 'clientareadetails', 'clientareacontacts', 'clientareaaddcontact']) ||
		($vars['templatefile'] == 'viewcart' && $vars['action'] == 'checkout'))
	) {
		if (!empty($vars['language']) && $vars['language'] == 'hungarian') {
			$retval .= '
			<script type="text/javascript">
				var vatnumber = "Adószám";
				var euvatnumber = "EU Adószám";
				var identity = "Személyi igazolvány szám";
			</script>
			';
		} else {
			$retval .= '
			<script type="text/javascript">
				var vatnumber = "VAT number";
				var euvatnumber = "EU VAT number";
				var identity = "Identity number";
			</script>
			';
		}
	}
	if (!empty($vars['templatefile']) && \in_array($vars['templatefile'], ['clientareadetails', 'clientareacontacts', 'clientareaaddcontact'])) {
		$retval .= '
		<script type="text/javascript">
			$(document).ready(function() {
				$("#country, #inputCompanyName").change(function () {
					if ($("#inputCompanyName").val() == "") {
						$("label[for=\'inputTaxId\']").text(identity);
					} else if($("#country").val() == "HU") {
						$("label[for=\'inputTaxId\']").text(vatnumber);
					} else {
						$("label[for=\'inputTaxId\']").text(euvatnumber);
					}
				});
				$("#country").trigger("change");
			});
		</script>
		';
	} elseif (
		!empty($vars['templatefile']) &&
		($vars['templatefile'] == 'clientregister' ||
		($vars['templatefile'] == 'viewcart' && $vars['action'] == 'checkout'))
	) {
		$retval .= '
		<script type="text/javascript">
			$(document).ready(function() {
				$("#inputCountry, #inputCompanyName").change(function () {
					if ($("#inputCompanyName").val() == "") {
						$("#inputTaxId").attr("placeholder",identity);
					} else if($("#inputCountry").val() == "HU") {
						$("#inputTaxId").attr("placeholder",vatnumber);
					} else {
						$("#inputTaxId").attr("placeholder",euvatnumber);
					}
				});
				$("#inputCountry").trigger("change");
			});
		</script>
		';
	} elseif (!empty($vars['templatefile']) && $vars['templatefile'] == 'viewcart' && $vars['action'] == 'checkout') {
		$retval .= '
		<script type="text/javascript">
			$(document).ready(function() {
				$("#inputDCCountry, #inputDCCompanyName").change(function () {
					if ($("#inputDCCompanyName").val() == "") {
						$("#inputDCTaxId").attr("placeholder",identity);
					} else if($("#inputDCCountry").val() == "HU") {
						$("#inputDCTaxId").attr("placeholder",vatnumber);
					} else {
						$("#inputDCTaxId").attr("placeholder",euvatnumber);
					}
				});
				$("#inputDCCountry").trigger("change");
			});
		</script>
		';
	}
	return $retval;
});
