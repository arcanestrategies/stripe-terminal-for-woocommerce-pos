!function(){if(jQuery&&jQuery.fn&&jQuery.fn.select2&&jQuery.fn.select2.amd)var e=jQuery.fn.select2.amd;e.define("select2/i18n/lt",[],function(){function n(e,i,n,t){return e%10==1&&(e%100<11||19<e%100)?i:2<=e%10&&e%10<=9&&(e%100<11||19<e%100)?n:t}return{inputTooLong:function(e){var i=e.input.length-e.maximum;return"Pašalinkite "+i+" simbol"+n(i,"į","ius","ių")},inputTooShort:function(e){var i=e.minimum-e.input.length;return"Įrašykite dar "+i+" simbol"+n(i,"į","ius","ių")},loadingMore:function(){return"Kraunama daugiau rezultatų…"},maximumSelected:function(e){return"Jūs galite pasirinkti tik "+e.maximum+" element"+n(e.maximum,"ą","us","ų")},noResults:function(){return"Atitikmenų nerasta"},searching:function(){return"Ieškoma…"},removeAllItems:function(){return"Pašalinti visus elementus"}}}),e.define,e.require}(),function(e,i){"object"==typeof exports&&"undefined"!=typeof module&&"function"==typeof require?i(require("../moment")):"function"==typeof define&&define.amd?define(["../moment"],i):i(e.moment)}(this,function(e){"use strict";var i={ss:"sekundė_sekundžių_sekundes",m:"minutė_minutės_minutę",mm:"minutės_minučių_minutes",h:"valanda_valandos_valandą",hh:"valandos_valandų_valandas",d:"diena_dienos_dieną",dd:"dienos_dienų_dienas",M:"mėnuo_mėnesio_mėnesį",MM:"mėnesiai_mėnesių_mėnesius",y:"metai_metų_metus",yy:"metai_metų_metus"};function s(e,i,n,t){return i?d(n)[0]:t?d(n)[1]:d(n)[2]}function r(e){return e%10==0||10<e&&e<20}function d(e){return i[e].split("_")}function n(e,i,n,t){var a=e+" ";return 1===e?a+s(0,i,n[0],t):i?a+(r(e)?d(n)[1]:d(n)[0]):t?a+d(n)[1]:a+(r(e)?d(n)[1]:d(n)[2])}return e.defineLocale("lt",{months:{format:"sausio_vasario_kovo_balandžio_gegužės_birželio_liepos_rugpjūčio_rugsėjo_spalio_lapkričio_gruodžio".split("_"),standalone:"sausis_vasaris_kovas_balandis_gegužė_birželis_liepa_rugpjūtis_rugsėjis_spalis_lapkritis_gruodis".split("_"),isFormat:/D[oD]?(\[[^\[\]]*\]|\s)+MMMM?|MMMM?(\[[^\[\]]*\]|\s)+D[oD]?/},monthsShort:"sau_vas_kov_bal_geg_bir_lie_rgp_rgs_spa_lap_grd".split("_"),weekdays:{format:"sekmadienį_pirmadienį_antradienį_trečiadienį_ketvirtadienį_penktadienį_šeštadienį".split("_"),standalone:"sekmadienis_pirmadienis_antradienis_trečiadienis_ketvirtadienis_penktadienis_šeštadienis".split("_"),isFormat:/dddd HH:mm/},weekdaysShort:"Sek_Pir_Ant_Tre_Ket_Pen_Šeš".split("_"),weekdaysMin:"S_P_A_T_K_Pn_Š".split("_"),weekdaysParseExact:!0,longDateFormat:{LT:"HH:mm",LTS:"HH:mm:ss",L:"YYYY-MM-DD",LL:"YYYY [m.] MMMM D [d.]",LLL:"YYYY [m.] MMMM D [d.], HH:mm [val.]",LLLL:"YYYY [m.] MMMM D [d.], dddd, HH:mm [val.]",l:"YYYY-MM-DD",ll:"YYYY [m.] MMMM D [d.]",lll:"YYYY [m.] MMMM D [d.], HH:mm [val.]",llll:"YYYY [m.] MMMM D [d.], ddd, HH:mm [val.]"},calendar:{sameDay:"[Šiandien] LT",nextDay:"[Rytoj] LT",nextWeek:"dddd LT",lastDay:"[Vakar] LT",lastWeek:"[Praėjusį] dddd LT",sameElse:"L"},relativeTime:{future:"po %s",past:"prieš %s",s:function(e,i,n,t){return i?"kelios sekundės":t?"kelių sekundžių":"kelias sekundes"},ss:n,m:s,mm:n,h:s,hh:n,d:s,dd:n,M:s,MM:n,y:s,yy:n},dayOfMonthOrdinalParse:/\d{1,2}-oji/,ordinal:function(e){return e+"-oji"},week:{dow:1,doy:4}})});