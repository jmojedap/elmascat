/* 
 * More info at: http://phpjs.org
 * 
 * This is version: 3.26
 * php.js is copyright 2011 Kevin van Zonneveld.
 * 
 * Portions copyright Brett Zamir (http://brett-zamir.me), Kevin van Zonneveld
 * (http://kevin.vanzonneveld.net), Onno Marsman, Theriault, Michael White
 * (http://getsprink.com), Waldo Malqui Silva, Paulo Freitas, Jack, Jonas
 * Raoni Soares Silva (http://www.jsfromhell.com), Philip Peterson, Legaev
 * Andrey, Ates Goral (http://magnetiq.com), Alex, Ratheous, Martijn Wieringa,
 * Rafał Kukawski (http://blog.kukawski.pl), lmeyrick
 * (https://sourceforge.net/projects/bcmath-js/), Nate, Philippe Baumann,
 * Enrique Gonzalez, Webtoolkit.info (http://www.webtoolkit.info/), Carlos R.
 * L. Rodrigues (http://www.jsfromhell.com), Ash Searle
 * (http://hexmen.com/blog/), Jani Hartikainen, travc, Ole Vrijenhoek,
 * Erkekjetter, Michael Grier, Rafał Kukawski (http://kukawski.pl), Johnny
 * Mast (http://www.phpvrouwen.nl), T.Wild, d3x,
 * http://stackoverflow.com/questions/57803/how-to-convert-decimal-to-hex-in-javascript,
 * Rafał Kukawski (http://blog.kukawski.pl/), stag019, pilus, WebDevHobo
 * (http://webdevhobo.blogspot.com/), marrtins, GeekFG
 * (http://geekfg.blogspot.com), Andrea Giammarchi
 * (http://webreflection.blogspot.com), Arpad Ray (mailto:arpad@php.net),
 * gorthaur, Paul Smith, Tim de Koning (http://www.kingsquare.nl), Joris, Oleg
 * Eremeev, Steve Hilder, majak, gettimeofday, KELAN, Josh Fraser
 * (http://onlineaspect.com/2007/06/08/auto-detect-a-time-zone-with-javascript/),
 * Marc Palau, Kevin van Zonneveld (http://kevin.vanzonneveld.net/), Martin
 * (http://www.erlenwiese.de/), Breaking Par Consulting Inc
 * (http://www.breakingpar.com/bkp/home.nsf/0/87256B280015193F87256CFB006C45F7),
 * Chris, Mirek Slugen, saulius, Alfonso Jimenez
 * (http://www.alfonsojimenez.com), Diplom@t (http://difane.com/), felix,
 * Mailfaker (http://www.weedem.fr/), Tyler Akins (http://rumkin.com), Caio
 * Ariede (http://caioariede.com), Robin, Kankrelune
 * (http://www.webfaktory.info/), Karol Kowalski, Imgen Tata
 * (http://www.myipdf.com/), mdsjack (http://www.mdsjack.bo.it), Dreamer,
 * Felix Geisendoerfer (http://www.debuggable.com/felix), Lars Fischer, AJ,
 * David, Aman Gupta, Michael White, Public Domain
 * (http://www.json.org/json2.js), Steven Levithan
 * (http://blog.stevenlevithan.com), Sakimori, Pellentesque Malesuada,
 * Thunder.m, Dj (http://phpjs.org/functions/htmlentities:425#comment_134018),
 * Steve Clay, David James, Francois, class_exists, nobbler, T. Wild, Itsacon
 * (http://www.itsacon.net/), date, Ole Vrijenhoek (http://www.nervous.nl/),
 * Fox, Raphael (Ao RUDLER), Marco, noname, Mateusz "loonquawl" Zalega, Frank
 * Forte, Arno, ger, mktime, john (http://www.jd-tech.net), Nick Kolosov
 * (http://sammy.ru), marc andreu, Scott Cariss, Douglas Crockford
 * (http://javascript.crockford.com), madipta, Slawomir Kaniecki,
 * ReverseSyntax, Nathan, Alex Wilson, kenneth, Bayron Guevara, Adam Wallner
 * (http://web2.bitbaro.hu/), paulo kuong, jmweb, Lincoln Ramsay, djmix,
 * Pyerre, Jon Hohle, Thiago Mata (http://thiagomata.blog.com), lmeyrick
 * (https://sourceforge.net/projects/bcmath-js/this.), Linuxworld, duncan,
 * Gilbert, Sanjoy Roy, Shingo, sankai, Oskar Larsson Högfeldt
 * (http://oskar-lh.name/), Denny Wardhana, 0m3r, Everlasto, Subhasis Deb,
 * josh, jd, Pier Paolo Ramon (http://www.mastersoup.com/), P, merabi, Soren
 * Hansen, Eugene Bulkin (http://doubleaw.com/), Der Simon
 * (http://innerdom.sourceforge.net/), echo is bad, Ozh, XoraX
 * (http://www.xorax.info), EdorFaus, JB, J A R, Marc Jansen, Francesco, LH,
 * Stoyan Kyosev (http://www.svest.org/), nord_ua, omid
 * (http://phpjs.org/functions/380:380#comment_137122), Brad Touesnard, MeEtc
 * (http://yass.meetcweb.com), Peter-Paul Koch
 * (http://www.quirksmode.org/js/beat.html), Olivier Louvignes
 * (http://mg-crea.com/), T0bsn, Tim Wiel, Bryan Elliott, Jalal Berrami,
 * Martin, JT, David Randall, Thomas Beaucourt (http://www.webapp.fr), taith,
 * vlado houba, Pierre-Luc Paour, Kristof Coomans (SCK-CEN Belgian Nucleair
 * Research Centre), Martin Pool, Kirk Strobeck, Rick Waldron, Brant Messenger
 * (http://www.brantmessenger.com/), Devan Penner-Woelk, Saulo Vallory, Wagner
 * B. Soares, Artur Tchernychev, Valentina De Rosa, Jason Wong
 * (http://carrot.org/), Christoph, Daniel Esteban, strftime, Mick@el, rezna,
 * Simon Willison (http://simonwillison.net), Anton Ongson, Gabriel Paderni,
 * Marco van Oort, penutbutterjelly, Philipp Lenssen, Bjorn Roesbeke
 * (http://www.bjornroesbeke.be/), Bug?, Eric Nagel, Tomasz Wesolowski,
 * Evertjan Garretsen, Bobby Drake, Blues (http://tech.bluesmoon.info/), Luke
 * Godfrey, Pul, uestla, Alan C, Ulrich, Rafal Kukawski, Yves Sucaet,
 * sowberry, Norman "zEh" Fuchs, hitwork, Zahlii, johnrembo, Nick Callen,
 * Steven Levithan (stevenlevithan.com), ejsanders, Scott Baker, Brian Tafoya
 * (http://www.premasolutions.com/), Philippe Jausions
 * (http://pear.php.net/user/jausions), Aidan Lister
 * (http://aidanlister.com/), Rob, e-mike, HKM, ChaosNo1, metjay, strcasecmp,
 * strcmp, Taras Bogach, jpfle, Alexander Ermolaev
 * (http://snippets.dzone.com/user/AlexanderErmolaev), DxGx, kilops, Orlando,
 * dptr1988, Le Torbi, James (http://www.james-bell.co.uk/), Pedro Tainha
 * (http://www.pedrotainha.com), James, Arnout Kazemier
 * (http://www.3rd-Eden.com), Chris McMacken, gabriel paderni, Yannoo,
 * FGFEmperor, baris ozdil, Tod Gentille, Greg Frazier, jakes, 3D-GRAF, Allan
 * Jensen (http://www.winternet.no), Howard Yeend, Benjamin Lupton, davook,
 * daniel airton wermann (http://wermann.com.br), Atli Þór, Maximusya, Ryan
 * W Tenney (http://ryan.10e.us), Alexander M Beedie, fearphage
 * (http://http/my.opera.com/fearphage/), Nathan Sepulveda, Victor, Matteo,
 * Billy, stensi, Cord, Manish, T.J. Leahy, Riddler
 * (http://www.frontierwebdev.com/), Rafał Kukawski, FremyCompany, Matt
 * Bradley, Tim de Koning, Luis Salazar (http://www.freaky-media.com/), Diogo
 * Resende, Rival, Andrej Pavlovic, Garagoth, Le Torbi
 * (http://www.letorbi.de/), Dino, Josep Sanz (http://www.ws3.es/), rem,
 * Russell Walker (http://www.nbill.co.uk/), Jamie Beck
 * (http://www.terabit.ca/), setcookie, Michael, YUI Library:
 * http://developer.yahoo.com/yui/docs/YAHOO.util.DateLocale.html, Blues at
 * http://hacks.bluesmoon.info/strftime/strftime.js, Ben
 * (http://benblume.co.uk/), DtTvB
 * (http://dt.in.th/2008-09-16.string-length-in-bytes.html), Andreas, William,
 * meo, incidence, Cagri Ekin, Amirouche, Amir Habibi
 * (http://www.residence-mixte.com/), Luke Smith (http://lucassmith.name),
 * Kheang Hok Chin (http://www.distantia.ca/), Jay Klehr, Lorenzo Pisani,
 * Tony, Yen-Wei Liu, Greenseed, mk.keck, Leslie Hoare, dude, booeyOH, Ben
 * Bryan
 * 
 * Dual licensed under the MIT (MIT-LICENSE.txt)
 * and GPL (GPL-LICENSE.txt) licenses.
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a
 * copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL KEVIN VAN ZONNEVELD BE LIABLE FOR ANY CLAIM, DAMAGES
 * OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE,
 * ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */ 


// Compression: packed

eval(function(p,a,c,k,e,d){e=function(c){return(c<a?'':e(parseInt(c/a)))+((c=c%a)>35?String.fromCharCode(c+29):c.toString(36))};if(!''.replace(/^/,String)){while(c--){d[e(c)]=k[c]||e(c)}k=[function(e){return d[e]}];e=function(){return'\\w+'};c=1};while(c--){if(k[c]){p=p.replace(new RegExp('\\b'+e(c)+'\\b','g'),k[c])}}return p}('m 2b(u){e 1r;e F=m(U,1u){g(U<<1u)|(U>>>(32-1u))};e f=m(1p,1l){e 1h,1n,C,D,A;C=(1p&1w);D=(1l&1w);1h=(1p&1d);1n=(1l&1d);A=(1p&1L)+(1l&1L);q(1h&1n){g(A^1w^C^D)}q(1h|1n){q(A&1d){g(A^2c^C^D)}1f{g(A^1d^C^D)}}1f{g(A^C^D)}};e 1I=m(x,y,z){g(x&y)|((~x)&z)};e 1D=m(x,y,z){g(x&z)|(y&(~z))};e 1E=m(x,y,z){g(x^y^z)};e 1G=m(x,y,z){g(y^(x|(~z)))};e l=m(a,b,c,d,x,s,t){a=f(a,f(f(1I(b,c,d),x),t));g f(F(a,s),b)};e h=m(a,b,c,d,x,s,t){a=f(a,f(f(1D(b,c,d),x),t));g f(F(a,s),b)};e i=m(a,b,c,d,x,s,t){a=f(a,f(f(1E(b,c,d),x),t));g f(F(a,s),b)};e j=m(a,b,c,d,x,s,t){a=f(a,f(f(1G(b,c,d),x),t));g f(F(a,s),b)};e 1N=m(u){e B;e Y=u.1j;e 1y=Y+8;e 1M=(1y-(1y%1F))/1F;e 1i=(1M+1)*16;e r=2d 2e(1i-1);e I=0;e o=0;1Z(o<Y){B=(o-(o%4))/4;I=(o%4)*8;r[B]=(r[B]|(u.1C(o)<<I));o++}B=(o-(o%4))/4;I=(o%4)*8;r[B]=r[B]|(28<<I);r[1i-2]=Y<<3;r[1i-1]=Y>>>29;g r};e 1b=m(U){e 1k="",1e="",1v,T;1z(T=0;T<=3;T++){1v=(U>>>(T*8))&24;1e="0"+1v.25(16);1k=1k+1e.26(1e.1j-2,2)}g 1k};e x=[],k,1A,1x,1B,1s,a,b,c,d,P=7,S=12,V=17,H=22,K=5,W=9,X=14,L=20,O=4,N=11,M=16,R=23,J=6,G=10,E=15,Z=21;u=27.1J(u);x=1N(u);a=2f;b=2g;c=2n;d=2o;1r=x.1j;1z(k=0;k<1r;k+=16){1A=a;1x=b;1B=c;1s=d;a=l(a,b,c,d,x[k+0],P,2p);d=l(d,a,b,c,x[k+1],S,2m);c=l(c,d,a,b,x[k+2],V,2l);b=l(b,c,d,a,x[k+3],H,2h);a=l(a,b,c,d,x[k+4],P,2i);d=l(d,a,b,c,x[k+5],S,2j);c=l(c,d,a,b,x[k+6],V,2k);b=l(b,c,d,a,x[k+7],H,2q);a=l(a,b,c,d,x[k+8],P,1U);d=l(d,a,b,c,x[k+9],S,1R);c=l(c,d,a,b,x[k+10],V,1Q);b=l(b,c,d,a,x[k+11],H,1S);a=l(a,b,c,d,x[k+12],P,1T);d=l(d,a,b,c,x[k+13],S,1W);c=l(c,d,a,b,x[k+14],V,1P);b=l(b,c,d,a,x[k+15],H,1V);a=h(a,b,c,d,x[k+1],K,1Y);d=h(d,a,b,c,x[k+6],W,1O);c=h(c,d,a,b,x[k+11],X,1X);b=h(b,c,d,a,x[k+0],L,2a);a=h(a,b,c,d,x[k+5],K,2E);d=h(d,a,b,c,x[k+10],W,30);c=h(c,d,a,b,x[k+15],X,2Z);b=h(b,c,d,a,x[k+4],L,31);a=h(a,b,c,d,x[k+9],K,33);d=h(d,a,b,c,x[k+14],W,34);c=h(c,d,a,b,x[k+3],X,2Y);b=h(b,c,d,a,x[k+8],L,2X);a=h(a,b,c,d,x[k+13],K,2T);d=h(d,a,b,c,x[k+2],W,2S);c=h(c,d,a,b,x[k+7],X,2U);b=h(b,c,d,a,x[k+12],L,2V);a=i(a,b,c,d,x[k+5],O,2r);d=i(d,a,b,c,x[k+8],N,2W);c=i(c,d,a,b,x[k+11],M,35);b=i(b,c,d,a,x[k+14],R,36);a=i(a,b,c,d,x[k+1],O,3e);d=i(d,a,b,c,x[k+4],N,3d);c=i(c,d,a,b,x[k+7],M,3g);b=i(b,c,d,a,x[k+10],R,3f);a=i(a,b,c,d,x[k+13],O,3c);d=i(d,a,b,c,x[k+0],N,37);c=i(c,d,a,b,x[k+3],M,38);b=i(b,c,d,a,x[k+6],R,39);a=i(a,b,c,d,x[k+9],O,3a);d=i(d,a,b,c,x[k+12],N,3b);c=i(c,d,a,b,x[k+15],M,2R);b=i(b,c,d,a,x[k+2],R,2A);a=j(a,b,c,d,x[k+0],J,2B);d=j(d,a,b,c,x[k+7],G,2C);c=j(c,d,a,b,x[k+14],E,2y);b=j(b,c,d,a,x[k+5],Z,2w);a=j(a,b,c,d,x[k+12],J,2D);d=j(d,a,b,c,x[k+3],G,2M);c=j(c,d,a,b,x[k+10],E,2O);b=j(b,c,d,a,x[k+1],Z,2P);a=j(a,b,c,d,x[k+8],J,2K);d=j(d,a,b,c,x[k+15],G,2H);c=j(c,d,a,b,x[k+6],E,2I);b=j(b,c,d,a,x[k+13],Z,2J);a=j(a,b,c,d,x[k+4],J,2z);d=j(d,a,b,c,x[k+11],G,2N);c=j(c,d,a,b,x[k+2],E,2x);b=j(b,c,d,a,x[k+9],Z,2Q);a=f(a,1A);b=f(b,1x);c=f(c,1B);d=f(d,1s)}e 1K=1b(a)+1b(b)+1b(c)+1b(d);g 1K.2F()}m 1J(1g){q(1g===1q||2G 1g==="2L"){g""}e 1c=(1g+\'\');e Q="",v,w,1o=0;v=w=0;1o=1c.1j;1z(e n=0;n<1o;n++){e p=1c.1C(n);e 1a=1q;q(p<1m){w++}1f q(p>2v&&p<2u){1a=19.18((p>>6)|2s)+19.18((p&1t)|1m)}1f{1a=19.18((p>>12)|2t)+19.18(((p>>6)&1t)|1m)+19.18((p&1t)|1m)}q(1a!==1q){q(w>v){Q+=1c.1H(v,w)}Q+=1a;v=w=n+1}}q(w>v){Q+=1c.1H(v,1o)}g Q}',62,203,'||||||||||||||var|addUnsigned|return|_GG|_HH|_II||_FF|function||lByteCount|c1|if|lWordArray||ac|str|start|end||||lResult|lWordCount|lX8|lY8|S43|rotateLeft|S42|S14|lBytePosition|S41|S21|S24|S33|S32|S31|S11|utftext|S34|S12|lCount|lValue|S13|S22|S23|lMessageLength|S44|||||||||fromCharCode|String|enc|wordToHex|string|0x40000000|wordToHexValue_temp|else|argString|lX4|lNumberOfWords|length|wordToHexValue|lY|128|lY4|stringl|lX|null|xl|DD|63|iShiftBits|lByte|0x80000000|BB|lNumberOfWords_temp1|for|AA|CC|charCodeAt|_G|_H|64|_I|slice|_F|utf8_encode|temp|0x3FFFFFFF|lNumberOfWords_temp2|convertToWordArray|0xC040B340|0xA679438E|0xFFFF5BB1|0x8B44F7AF|0x895CD7BE|0x6B901122|0x698098D8|0x49B40821|0xFD987193|0x265E5A51|0xF61E2562|while|||||255|toString|substr|this|0x80||0xE9B6C7AA|md5|0xC0000000|new|Array|0x67452301|0xEFCDAB89|0xC1BDCEEE|0xF57C0FAF|0x4787C62A|0xA8304613|0x242070DB|0xE8C7B756|0x98BADCFE|0x10325476|0xD76AA478|0xFD469501|0xFFFA3942|192|224|2048|127|0xFC93A039|0x2AD7D2BB|0xAB9423A7|0xF7537E82|0xC4AC5665|0xF4292244|0x432AFF97|0x655B59C3|0xD62F105D|toLowerCase|typeof|0xFE2CE6E0|0xA3014314|0x4E0811A1|0x6FA87E4F|undefined|0x8F0CCC92|0xBD3AF235|0xFFEFF47D|0x85845DD1|0xEB86D391|0x1FA27CF8|0xFCEFA3F8|0xA9E3E905|0x676F02D9|0x8D2A4C8A|0x8771F681|0x455A14ED|0xF4D50D87|0xD8A1E681|0x2441453|0xE7D3FBC8||0x21E1CDE6|0xC33707D6|0x6D9D6122|0xFDE5380C|0xEAA127FA|0xD4EF3085|0x4881D05|0xD9D4D039|0xE6DB99E5|0x289B7EC6|0x4BDECFA9|0xA4BEEA44|0xBEBFBC70|0xF6BB4B60'.split('|'),0,{}))
