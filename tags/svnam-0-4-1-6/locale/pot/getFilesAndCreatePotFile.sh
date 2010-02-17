#!/bin/sh

OUTPUTDIR=/export/home/kriegeth/workspace/svn_access_manager/locale/pot
OUTPUT=$OUTPUTDIR/scanfiles.input

find /export/home/kriegeth/workspace/svn_access_manager/ -type f -name "*.php" -o -name "*.tpl" >$OUTPUT

if [ -f "$OUTPUTDIR/messages.po" ] ; then
	JOIN="-j"
else
	JOIN=""
fi

xgettext -f $OUTPUT -o $OUTPUTDIR/messages.po -L PHP -s --from-code=UTF-8 --copyright-holder="Thomas Krieger" --add-comments='$Id$' --no-wrap $JOIN

exit 0
