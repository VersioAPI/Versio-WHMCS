{if $errorMessage} {$errorMessage}
{else}

{if $url == "downloadssl.php"}
{$buttonname = "Download Certificate"}
{$certificate->certificate}
{else}

{if $url == "reissuessl.php"}
{$buttonname = "Reissue Certificate"}
{/if}

{if $url == "requestssl.php"}
{$buttonname = "Request Certificate"}
{/if}

{if $url == "changeapproverssl.php"}
{$buttonname = "Change approver"}
{/if}

{if $step == null}
<form action="{$url}?serviceId={$serviceid}&step=2" method="post">
<table class="frame" width="100%" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="10" border="0">
                    <tbody>
                        <tr>
						<td>Domain</td>
                            <td>
							<input type="text" name="domain"></td>
                        </tr>
						<tr>
						<td><input type="submit" value="Step 2"></td>
                        </tr>

                    </tbody>
                </table>
				
            </td>
        </tr>
    </tbody>
</table>
</form>
{elseif $step == 2}
<form action="{$url}?serviceId={$serviceid}&step=3" method="post">
<table class="frame" width="100%" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="10" border="0">
                    <tbody>
						{if $url != "changeapproverssl.php"}
                        <tr>
                            <td>CSR code</td>
                            <td><textarea type="textarea" name="csr"></textarea></td>
                        </tr>
                        <tr>
                            <td>Street + Housenumber</td>
                            <td><input type="text" name="address"></td>
                        </tr>
						<tr>
                            <td>Postalcode</td>
                            <td><input type="text" name="postalcode"></td>
                        </tr>
						{/if}
						{if $url == "requestssl.php"}
                        <tr>
                            <td>Contactperson</td>
                            <td><input type="text" name="contactperson"></td>
                        </tr>
                        <tr>
                            <td>Contactperson e-mail</td>
                            <td><input type="text" name="contactperson_email"></td>
                        </tr>
						<tr>
                            <td>Contactperson phone</td>
                            <td><input type="text" name="contactperson_phone"></td>
                        </tr>
						{/if}
						<tr>
						<td>E-mail for validation</td>
                            <td>
							<select name="approver_email">
							{foreach from=$sslapprovers item=email}
							<option value="{$email}">{$email}</option>
							{/foreach}
							</select></td>
                        </tr>
						<tr>
						<td><input type="submit" value="{$buttonname}"></td>
                        </tr>
                    </tbody>
                </table>
				
            </td>
        </tr>
    </tbody>
</table>
</form>
{elseif $step == 3}
{$message}
{/if}

{/if}

{/if}