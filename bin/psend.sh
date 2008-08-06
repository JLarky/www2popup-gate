{ # settings
export HOME=/home/jlarky
export LANG=ru_RU.UTF-8
export SROOT=/var/www/gate/bin
export MROOT=/tmp/psend
# сообщение по умолчанию (default message)
export DMSG=/var/www/gate/bin/std/msg
}

{ # preparing files
rm $SROOT/tosend.again 2>/dev/null
touch $SROOT/tosend.again

cat $SROOT/tosend.add >> $SROOT/tosend.list 2>/dev/null
rm -f $SROOT/tosend.add 2>/dev/null
}

while read compto compfrom msgf
do
if test -n "$compto"
then

mkdir -p /tmp/psend/
chmod 777 /tmp/psend/
cp $DMSG $MROOT/default

if ! test -n "$msgf"
then
msgf='default'
fi

if ! test -n "$compfrom"
then
compfrom='JLarky'
fi

export POPUPFROM=$compfrom
export POPUPTO=$compto
export MSGFNAME=$MROOT/$msgf

if [ `$SROOT/sender.sh | grep 1` ]
then
# All right
  date >> $SROOT/psend.log
  echo "All Right for $compto" >> $SROOT/psend.log
  echo "---------------------" >> $SROOT/psend.log
 rm $MROOT"/"$msgf
else
# Nothing right, will try again
  echo "$compto $compfrom $msgf" >> $SROOT/tosend.again
fi

fi
done  < $SROOT/tosend.list

# mv $SROOT/tosend.again $SROOT/tosend.list
cat $SROOT/tosend.again | sort |uniq > $SROOT/tosend.list
rm  $SROOT/tosend.again
