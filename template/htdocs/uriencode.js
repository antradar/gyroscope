window.encodeURIComponent = function (s) {
    var okURIchars = /[a-z\d\.!~\*'\(\)_-]/i;
    var hexchars = "0123456789ABCDEF";

    var toHex = function ( n ) { return hexchars.charAt( n >> 4 ) + hexchars.charAt( n & 0xF ); }

    var utf8 = function ( wide ) {
        var c, s;
        var enc = "";
        var i = 0;
        while ( i < wide.length ) {
            c = wide.charCodeAt( i++ );
            // handle UTF-16 surrogates
            if ( c >= 0xDC00 && c < 0xE000 ) continue;
            if ( c >= 0xD800 && c < 0xDC00 ) {
                if ( i >= wide.length ) continue;
                s = wide.charCodeAt( i++ );
                if ( s < 0xDC00 || c >= 0xDE00 ) continue;
                c = ( ( c - 0xD800 ) << 10 ) + ( s - 0xDC00 ) + 0x10000;
            }
            // output value
            if ( c < 0x80 ) { enc += String.fromCharCode( c );
            } else if ( c < 0x800 ) { enc += String.fromCharCode( 0xC0 + ( c >> 6 ), 0x80 + ( c & 0x3F ) );
            } else if ( c < 0x10000 ) { enc += String.fromCharCode( 0xE0 + ( c >> 12 ), 0x80 + ( c >> 6 & 0x3F ), 0x80 + ( c & 0x3F ) );
            } else { enc += String.fromCharCode( 0xF0 + ( c >> 18 ), 0x80 + ( c >> 12 & 0x3F ), 0x80+ ( c >> 6 & 0x3F ), 0x80 + ( c & 0x3F ) ); }
        }
        return enc;
    }

    var s = utf8( s );
    var enc = "";
    for ( var i = 0; i < s.length; i++ ) {
        if ( !( s.charAt( i ).match( okURIchars ) ) ) {
            enc += "%" + toHex( s.charCodeAt( i ) );
        } else {
            enc += s.charAt( i );
        }
    }
    return enc;
}

window.decodeURIComponent=function(s){return unescape(s);}