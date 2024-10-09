<style>
@media only screen and (max-width: 600px) {
    .main {
        width: 320px !important;
    }

    .top-image {
        width: 100% !important;
    }
    .inside-footer {
        width: 320px !important;
    }
    table[class="contenttable"] { 
        width: 320px !important;
        text-align: left !important;
    }
    td[class="force-col"] {
        display: block !important;
    }
        td[class="rm-col"] {
        display: none !important;
    }
    .mt {
        margin-top: 15px !important;
    }
    *[class].width300 {width: 255px !important;}
    *[class].block {display:block !important;}
    *[class].blockcol {display:none !important;}
    .emailButton{
        width: 100% !important;
    }

    .emailButton a {
        display:block !important;
        font-size:18px !important;
    }
}
</style>
        
<body link="#F53E3E" vlink="#F53E3E" alink="#F53E3E">
    <table class=" main contenttable" align="center" style="font-weight: normal;border-collapse: collapse;border: 0;margin-left: auto;margin-right: auto;padding: 0;font-family: Arial, sans-serif;color: #555559;background-color: white;font-size: 16px;line-height: 26px;width: 600px;">
        <tr>
            <td class="border" style="border-collapse: collapse;border: 1px solid #eeeff0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                    <tr>
                        <td colspan="4" valign="top" class="image-section" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background-color: #fff;border-bottom: 4px solid #F53E3E">
                            <a href="{{ url('') }}"><img class="top-image" src="https://tukuklik.com/public/uploads/settings/639888cb0f3c3.png" style="line-height: 1;width: 250px;margin-bottom:10px;"></a>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" class="side title" style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;vertical-align: top;background-color: white;border-top: none;">
                            <table style="width:100%;font-weight: normal;border-collapse: collapse;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                                <tr>
                                    <td class="head-title" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 28px;line-height: 34px;font-weight: bold; text-align: center;">
                                        <div class="mktEditable" id="main_title">
                                            {{ $data['messageTitle'] }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="top-padding" style="border-collapse: collapse;border: 0;margin: 0;padding: 15px 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 21px;">
                                        <hr size="1" color="#eeeff0" style="width:100%">
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                                        <div class="mktEditable" id="main_text">
                                            Hello {{ $data['name'] }},<br>
                                            {{ $data['message'] }}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td valign="top" class="side title" style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;vertical-align: top;background-color: white;border-top: none;">
                            <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                                <tr>
                                    <td class="text" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                                        <div class="mktEditable" id="main_text">
                                            <b>No Penawaran</b>
                                        </div>
                                    </td>
                                    <td class="text" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                                        <div class="mktEditable" id="main_text" style="margin-left:50px;">
                                            {{ $quotation->number }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                                        <div class="mktEditable" id="main_text">
                                            <b>Nominal Penawaran</b>
                                        </div>
                                    </td>
                                    <td class="text" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                                        <div class="mktEditable" id="main_text" style="margin-left:50px;">
                                            {{ number_format($quotation->grand_total, 2,'.',',') }}
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="text" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                                        <div class="mktEditable" id="main_text">
                                            <br><b>Informasi Negosiasi</b>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>	
                    <tr>
                        <td>
                        <ol style="padding: 15px;list-style: none;">
                            @foreach($quotation->negotiations()->orderByDesc('created_at')->get() as $nego)
                                <li style="position: relative; padding-bottom: 20px; padding-left: 30px; border-left: 2px solid #e4e8eb;">
                                    <time style="display: block; position: relative; top: -5px; color: #8c96a3; text-transform: uppercase; font-size: 13px;" datetime="9-25">{{ date('d F Y H:i:s', strtotime($nego->created_at)) }}</time>
                                    <span style="position: relative;top: -3px;">{{ $nego->user->name }} - {{ $nego->user_id == $quotation->user_id  ? 'Pembeli' : 'Penjual'  }} : <a href="single-need.php">"{{ $nego->description }}"</a></span>
                                </li>
                            @endforeach                            
                          </ol>
                        </td>
                    </tr>	
                    <tr>
                        <td valign="top" class="side title" style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;vertical-align: top;background-color: white;border-top: none;">
                            <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                                <tr>
                                    <td colspan="2" class="text" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                                        <div class="mktEditable" id="main_text">
                                            Klik Link <a href="{{ $data['url'] }}">Berikut</a> Untuk Informasi Lebih Detail<br><br>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" class="text" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                                        <div class="mktEditable" id="main_text">
                                            Demikian Informasi Yang Bisa Kami Sampaikan. Jika Ada Kesalahan Silahkan Hubungi customer@tukuklik.com
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>								
                    <tr>
                        <td valign="top" align="center" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;">
                            <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                                <tr>
                                    <td align="center" valign="middle" class="social" style="border-collapse: collapse;border: 0;margin: 0;padding: 10px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;text-align: center;">
                                        <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                                            <tr>
                                                <td style="border-collapse: collapse;border: 0;margin: 0;padding: 5px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;"><a href="https://www.tenable.com/blog"><img src="https://info.tenable.com/rs/tenable/images/rss-teal.png"></a></td>
                                                <td style="border-collapse: collapse;border: 0;margin: 0;padding: 5px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;"><a href="https://twitter.com/tenablesecurity"><img src="https://info.tenable.com/rs/tenable/images/twitter-teal.png"></a></td>
                                                <td style="border-collapse: collapse;border: 0;margin: 0;padding: 5px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;"><a href="https://www.facebook.com/Tenable.Inc"><img src="https://info.tenable.com/rs/tenable/images/facebook-teal.png"></a></td>
                                                <td style="border-collapse: collapse;border: 0;margin: 0;padding: 5px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;"><a href="https://www.youtube.com/tenablesecurity"><img src="https://info.tenable.com/rs/tenable/images/youtube-teal.png"></a></td>
                                                <td style="border-collapse: collapse;border: 0;margin: 0;padding: 5px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;"><a href="https://www.linkedin.com/company/tenable-network-security"><img src="https://info.tenable.com/rs/tenable/images/linkedin-teal.png"></a></td>
                                                <td style="border-collapse: collapse;border: 0;margin: 0;padding: 5px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;"><a href="https://plus.google.com/107158297098429070217"><img src="https://info.tenable.com/rs/tenable/images/google-teal.png"></a></td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr bgcolor="#fff" style="border-top: 4px solid #F53E3E;">
                        <td valign="top" class="footer" style="border-collapse: collapse;border: 0;margin: 0;padding: 0;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 16px;line-height: 26px;background: #fff;text-align: center;">
                            <table style="font-weight: normal;border-collapse: collapse;border: 0;margin: 0;padding: 0;font-family: Arial, sans-serif;">
                                <tr>
                                    <td class="inside-footer" align="center" valign="middle" style="border-collapse: collapse;border: 0;margin: 0;padding: 20px;-webkit-text-size-adjust: none;color: #555559;font-family: Arial, sans-serif;font-size: 12px;line-height: 16px;vertical-align: middle;text-align: center;width: 580px;">
                                        <div id="address" class="mktEditable">
                                        <b>Copyright &copy; {{ date('Y') }} All Rights Reserved </b><br>
                                            {{-- 7021 Columbia Gateway Drive<br>  Suite 500 <br> Columbia, MD 21046<br>
                                            <a style="color: #F53E3E;" href="https://www.tenable.com/contact-tenable">Contact Us</a> --}}
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>