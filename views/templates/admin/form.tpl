
{*
* 2017 mpSOFT
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    mpSOFT <info@mpsoft.it>
*  @copyright 2017 mpSOFT Massimiliano Palermo
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of mpSOFT
*}
<style>
    .table-list
    {
        border: 1px solid #aaaaaa;
    }
    .table-list tbody td
    {
        padding: 3px;
        padding-left: 5px;
    }
</style>
<form id="dbclean_form" method="POST" class="defaultForm form-horizontal mpdbclean">
    <!-- 
    /**
     *
     * LANGUAGES PANEL
     *
     **
    -->
    <div class="panel" id="form_languages">
        <div class="panel-heading">
            <img src="{$img_folder|escape:'htmlall':'UTF-8'}languages.png">
            <span>{l s='Languages' mod='mpdbclean'}</span>
        </div>
        {if !empty($msg_languages)}
            {$msg_languages|escape:'htmlall':'UTF-8'}
        {/if}
        <div class='form-wrapper' style='overflow: hidden;'>
            <br>
            <label class='control-label'>{l s='If you want to delete a language, please deactivate it first.' mod='mpdbclean'}</label>
            <br><br>
            <table class='table-list'>
                <tbody>
                    {foreach $lang_rows as $row}
                        <tr>
                            <td><input type='checkbox' name='checkLang[]'></td>
                            <td><img src="{$img_lang_dir|escape:'htmlall':'UTF-8'}{$row['id_lang']|escape:'htmlall':'UTF-8'}.jpg"></td>
                            <td class='hidden'>{$row['id_lang']|escape:'htmlall':'UTF-8'}</td>
                            <td>{$row['name']|escape:'htmlall':'UTF-8'}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
            <br>
            <button type="button" class='btn btn-default btn-group' id='langCheckAll'>
                <i class='process-icon-ok'></i>
                {l s='Select all' mod='mpdbclean'}
            </button>
            <button type="button" class='btn btn-default btn-group' id='langCheckNone'>
                <i class='process-icon-cancel'></i>
                {l s='Select none' mod='mpdbclean'}
            </button>
            <button type="button" class='btn btn-default btn-group' id='langCheckInvert'>
                <i class='process-icon-refresh'></i>
                {l s='Select Inverse' mod='mpdbclean'}
            </button>
            <br>
            <input type='hidden' name='txtLanguageList' value='' id='txtLanguageList'>            
        </div>
        <div class="panel-footer">
            <button type="submit" value="1" id="submit_languages" name="submit_languages" class="btn btn-default pull-right">
                <i class="process-icon-cancel"></i>
                {l s='DELETE SELECTED' mod='mpdbclean'}
            </button>
        </div>
    </div>
    <!-- 
    /**
     *
     * CARRIERS PANEL
     *
     **
    -->
    <div class="panel" id="form_carriers">
        <div class="panel-heading">
            <img src="{$img_folder|escape:'htmlall':'UTF-8'}truck.png">
            <span>{l s='Carriers' mod='mpdbclean'}</span>
        </div>
        {if !empty($msg_carriers)}
            {$msg_carriers|escape:'htmlall':'UTF-8'}
        {/if}
        <div class='form-wrapper' style='overflow: hidden;'>
            <br>
            <label class='control-label'>{l s='If you want to delete a carrier, please deactivate it first.' mod='mpdbclean'}</label>
            <br><br>
            <table class='table-list'>
                <tbody>
                    {foreach $carrier_rows as $row}
                        <tr>
                            <td><input type='checkbox' name='checkCarrier[]'></td>
                            <td><img src="{$img_carrier_dir|escape:'htmlall':'UTF-8'}{$row['id_carrier']|escape:'htmlall':'UTF-8'}.jpg" style="width: 16px; height: 16px; object-fit: cover;"></td>
                            <td class='hidden'>{$row['id_carrier']|escape:'htmlall':'UTF-8'}</td>
                            <td>{$row['name']|escape:'htmlall':'UTF-8'}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
            <br>
            <button type="button" class='btn btn-default btn-group' id='carrierCheckAll'>
                <i class='process-icon-ok'></i>
                {l s='Select all' mod='mpdbclean'}
            </button>
            <button type="button" class='btn btn-default btn-group' id='carrierCheckNone'>
                <i class='process-icon-cancel'></i>
                {l s='Select none' mod='mpdbclean'}
            </button>
            <button type="button" class='btn btn-default btn-group' id='carrierCheckInvert'>
                <i class='process-icon-refresh'></i>
                {l s='Select Inverse' mod='mpdbclean'}
            </button>
            <br>
            <div>
                <label class='control-label'>{l s='Change carrier to.' mod='mpdbclean'}</label>
            </div>
            <div>
                <select name='selUpdateCarrier' id='selUpdateCarrier'>
                    <option value='0'>{l s='Please select a carrier' mod='mpdbclean'}</option>
                    {foreach $carrier_enabled as $carrier}
                        <option value="{$carrier['id_carrier']|escape:'htmlall':'UTF-8'}">{$carrier['name']|escape:'htmlall':'UTF-8'}</option>
                    {/foreach}
                </select>
                <p style='font-size: 0.8em; font-style: italic;'>{l s='Change carrier to selected one after deletion' mod='mpdbclean'}</p>
            </div>
                
            <input type='hidden' name='txtCarrierList' value='' id='txtCarrierList'>            
        </div>
        <div class="panel-footer">
            <button type="submit" value="1" id="submit_carriers" name="submit_carriers" class="btn btn-default pull-right">
                <i class="process-icon-cancel"></i>
                {l s='DELETE SELECTED' mod='mpdbclean'}
            </button>
        </div>
    </div>
    <!-- 
    /**
     *
     * CURRENCIES PANEL
     *
     **
    -->
    <div class="panel" id="form_currencies">
        <div class="panel-heading">
            <img src="{$img_folder|escape:'htmlall':'UTF-8'}currencies.png">
            <span>{l s='Currencies' mod='mpdbclean'}</span>
        </div>
        {if !empty($msg_currencies)}
            {$msg_currencies|escape:'htmlall':'UTF-8'}
        {/if}
        <div class='form-wrapper' style='overflow: hidden;'>
            <br>
            <label class='control-label'>{l s='If you want to delete a currency, please deactivate it first.' mod='mpdbclean'}</label>
            <br><br>
            <table class='table-list'>
                <tbody>
                    {foreach $currency_rows as $row}
                        <tr>
                            <td><input type='checkbox' name='checkCurrency[]'></td>
                            <td><img src="{$img_folder|escape:'htmlall':'UTF-8'}money.png" style="width: 16px; height: 16px; object-fit: cover;"></td>
                            <td class='hidden'>{$row['id_currency']|escape:'htmlall':'UTF-8'}</td>
                            <td>{$row['name']|escape:'htmlall':'UTF-8'}</td>
                            <td>{$row['iso_code']|escape:'htmlall':'UTF-8'}</td>
                            <td><strong>{$row['sign']|escape:'htmlall':'UTF-8'}</strong></td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>
            <br>
            <button type="button" class='btn btn-default btn-group' id='currencyCheckAll'>
                <i class='process-icon-ok'></i>
                {l s='Select all' mod='mpdbclean'}
            </button>
            <button type="button" class='btn btn-default btn-group' id='currencyCheckNone'>
                <i class='process-icon-cancel'></i>
                {l s='Select none' mod='mpdbclean'}
            </button>
            <button type="button" class='btn btn-default btn-group' id='currencyCheckInvert'>
                <i class='process-icon-refresh'></i>
                {l s='Select Inverse' mod='mpdbclean'}
            </button>
            <br>
            <div>
                <label class='control-label'>{l s='Change currency to.' mod='mpdbclean'}</label>
            </div>
            <div>
                <select name='selUpdateCurrency' id='selUpdateCurrency'>
                    <option value='0'>{l s='Please select a currency' mod='mpdbclean'}</option>
                    {foreach $currency_enabled as $currency}
                        <option value="{$currency['id_currency']|escape:'htmlall':'UTF-8'}">{$currency['name']|escape:'htmlall':'UTF-8'}</option>
                    {/foreach}
                </select>
                <p style='font-size: 0.8em; font-style: italic;'>{l s='Change currency to selected one after deletion' mod='mpdbclean'}</p>
            </div>
            <input type='hidden' name='txtCurrencyList' value='' id='txtCurrencyList'>            
        </div>
        <div class="panel-footer">
            <button type="submit" value="1" id="submit_currencies" name="submit_currencies" class="btn btn-default pull-right">
                <i class="process-icon-cancel"></i>
                {l s='DELETE SELECTED' mod='mpdbclean'}
            </button>
        </div>
    </div>
</form>
        
<script type='text/javascript'>
    var ids = new Array();
    $(document).ready(function()
    {
        /**
         * Submit Languages
         */
        $("#submit_languages").on("click",function(e)
        {
            e.preventDefault();
            if(confirm("{l s='Delete selected languages?' mod='mpdbclean'}"))
            {
                submit = true;
                $('#dbclean_form').submit();
            }
        });
        /**
         * Submit Carriers
         */
        $("#submit_carriers").on("click",function(e)
        {
            e.preventDefault();
            if($('#selUpdateCarrier').val()==='0')
            {
                alert("{l s='Please, select a carrier change first.' mod='mpdbclean'}");
                return false;
            }
            if(confirm("{l s='Delete selected carriers?' mod='mpdbclean'}"))
            {
                submit = true;
                $('#dbclean_form').submit();
            }
        });
        /**
         * Submit Currency
         */
        $("#submit_currencies").on("click",function(e)
        {
            e.preventDefault();
            if($('#selUpdateCurrency').val()==='0')
            {
                alert("{l s='Please, select a currency change first.' mod='mpdbclean'}");
                return false;
            }
            if(confirm("{l s='Delete selected currencies?' mod='mpdbclean'}"))
            {
                submit = true;
                $('#dbclean_form').submit();
            }
        });
        /**
         * Language check buttons
         */
        $("input[name='checkLang[]']").on("click",function()
        {
            ids = new Array();
            $("input[name='checkLang[]']").each(function()
            {
                if($(this).is(":checked"))
                {
                    ids.push($(this).parent().next().next().text());
                }
            });
            $("#txtLanguageList").val(ids.join(","));
        });
        $("#langCheckAll").on("click",function()
        {
            ids = new Array();
            $("input[name='checkLang[]']").each(function()
            {
                $(this).prop("checked",true);
                ids.push($(this).parent().next().next().text());
            });
            $("#txtLanguageList").val(ids.join(","));
        });
        $("#langCheckNone").on("click",function()
        {
            $("input[name='checkLang[]']").each(function()
            {
                $(this).prop("checked",false);
            });
            $("#txtLanguageList").val('');
        });
        $("#langCheckInvert").on("click",function()
        {
            ids = new Array();
            $("input[name='checkLang[]']").each(function()
            {
                $(this).prop("checked",!$(this).prop("checked"));
                if($(this).prop("checked"))
                {
                    ids.push($(this).parent().next().next().text());
                }
            });
            $("#txtLanguageList").val(ids.join(","));
        });
        /**
         * Carriers check buttons
         */
        $("input[name='checkCarrier[]']").on("click",function()
        {
            ids = new Array();
            $("input[name='checkCarrier[]']").each(function()
            {
                if($(this).is(":checked"))
                {
                    ids.push($(this).parent().next().next().text());
                }
            });
            $("#txtCarrierList").val(ids.join(","));
        });
        $("#carrierCheckAll").on("click",function()
        {
            ids = new Array();
            $("input[name='checkCarrier[]']").each(function()
            {
                $(this).prop("checked",true);
                ids.push($(this).parent().next().next().text());
            });
            $("#txtCarrierList").val(ids.join(","));
        });
        $("#carrierCheckNone").on("click",function()
        {
            $("input[name='checkCarrier[]']").each(function()
            {
                $(this).prop("checked",false);
            });
            $("#txtCarrierList").val('');
        });
        $("#carrierCheckInvert").on("click",function()
        {
            ids = new Array();
            $("input[name='checkCarrier[]']").each(function()
            {
                $(this).prop("checked",!$(this).prop("checked"));
                if($(this).prop("checked"))
                {
                    ids.push($(this).parent().next().next().text());
                }
            });
            $("#txtCarrierList").val(ids.join(","));
        });
        /**
         * Currency check buttons
         */
        $("input[name='checkCurrency[]']").on("click",function()
        {
            ids = new Array();
            $("input[name='checkCurrency[]']").each(function()
            {
                if($(this).is(":checked"))
                {
                    ids.push($(this).parent().next().next().text());
                }
            });
            $("#txtCurrencyList").val(ids.join(","));
        });
        $("#currencyCheckAll").on("click",function()
        {
            ids = new Array();
            $("input[name='checkCurrency[]']").each(function()
            {
                $(this).prop("checked",true);
                ids.push($(this).parent().next().next().text());
            });
            $("#txtCurrencyList").val(ids.join(","));
        });
        $("#currencyCheckNone").on("click",function()
        {
            $("input[name='checkCurrency[]']").each(function()
            {
                $(this).prop("checked",false);
            });
            $("#txtCurrencyList").val('');
        });
        $("#currencyCheckInvert").on("click",function()
        {
            ids = new Array();
            $("input[name='checkCurrency[]']").each(function()
            {
                $(this).prop("checked",!$(this).prop("checked"));
                if($(this).prop("checked"))
                {
                    ids.push($(this).parent().next().next().text());
                }
            });
            $("#txtCurrencyList").val(ids.join(","));
        });
    });
</script>