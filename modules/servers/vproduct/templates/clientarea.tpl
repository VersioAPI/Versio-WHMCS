<h2 align="left">SSL Certificate Status</h2>

{if $errorMessage} {$errorMessage} {/if}


<table class="frame" width="100%" cellspacing="0" cellpadding="0">
    <tbody>
        <tr>
            <td>
                <table width="100%" cellspacing="0" cellpadding="10" border="0">
                    <tbody>
                        <tr>
                            <td class="fieldarea" width="150">SSL Panel:</td>
                            <td><a href="{$systemurl|regex_replace:"/\/$/":""}{$linkValue}" target="_blank">{$linkName}</a> {if isset($linkValue2)} - <a href="{$systemurl|regex_replace:"/\/$/":""}{$linkValue2}" target="_blank">{$linkName2}</a> {/if}</td>
                        </tr>
                        <tr>
                            <td class="fieldarea" width="150">Status:</td>
                            <td>{$status}</td>
                        </tr>
                        <tr>
                            <td class="fieldarea" width="150">Created at:</td>
                            <td>{$creationDate}</td>
                        </tr>
                        <tr>
                            <td class="fieldarea" width="150">Activated at:</td>
                            <td>{$activationDate}</td>
                        </tr>
                        <tr>
                            <td class="fieldarea" width="150">Expire at:</td>
                            <td>{$expirationDate}</td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
    </tbody>
</table>
