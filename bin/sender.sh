export HOME=/home/jlarky
export LANG=ru_RU.KOI8-R

pfrom=""
for word in $POPUPFROM
do
if (test -s $pfrom)
then pfrom=$word
fi
done

pto=""
for word in $POPUPTO
do
if (test -s $pto)
then pto=$word
fi
done

#msgf=""
#for word in $MSGFNAME
#do
#if (test -s $msgf)
#then msgf=$word
#fi
#done
msgf=$MSGFNAME

if test -r $msgf
then
cat $msgf
else
echo "по какиим-то причинам текст сообщения от этого пользователя утерян (с) http://proxy/bot"
fi | /usr/local/bin/popupnicheg -m $pfrom -H 0:e0:29:2e:82:88 $pto | grep -c 1/1
