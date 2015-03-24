{\rtf1\ansi\ansicpg1252\cocoartf949\cocoasubrtf540
{\fonttbl\f0\fswiss\fcharset0 Helvetica;}
{\colortbl;\red255\green255\blue255;}
\paperw11900\paperh16840\margl1440\margr1440\vieww9000\viewh8400\viewkind0
\pard\tx566\tx1133\tx1700\tx2267\tx2834\tx3401\tx3968\tx4535\tx5102\tx5669\tx6236\tx6803\ql\qnatural\pardirnatural

\f0\fs24 \cf0 function currencyFormat(fld, milSep, decSep, e) \{\
  var sep = 0;\
  var key = '';\
  var i = j = 0;\
  var len = len2 = 0;\
  var strCheck = '0123456789';\
  var aux = aux2 = '';\
  var whichCode = (window.Event) ? e.which : e.keyCode;\
\
  if (whichCode == 13) return true;  // Enter\
  if (whichCode == 8) return true;  // Delete\
  key = String.fromCharCode(whichCode);  // Get key value from key code\
  if (strCheck.indexOf(key) == -1) return false;  // Not a valid key\
  len = fld.value.length;\
  for(i = 0; i < len; i++)\
  if ((fld.value.charAt(i) != '0') && (fld.value.charAt(i) != decSep)) break;\
  aux = '';\
  for(; i < len; i++)\
  if (strCheck.indexOf(fld.value.charAt(i))!=-1) aux += fld.value.charAt(i);\
  aux += key;\
  len = aux.length;\
  if (len == 0) fld.value = '';\
  if (len == 1) fld.value = '0'+ decSep + '0' + aux;\
  if (len == 2) fld.value = '0'+ decSep + aux;\
  if (len > 2) \{\
    aux2 = '';\
    for (j = 0, i = len - 3; i >= 0; i--) \{\
      if (j == 3) \{\
        aux2 += milSep;\
        j = 0;\
      \}\
      aux2 += aux.charAt(i);\
      j++;\
    \}\
    fld.value = '';\
    len2 = aux2.length;\
    for (i = len2 - 1; i >= 0; i--)\
    fld.value += aux2.charAt(i);\
    fld.value += decSep + aux.substr(len - 2, len);\
  \}\
  return false;\
\}\
}