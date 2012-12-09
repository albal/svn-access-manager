#!/bin/sh

find ../../ -type f -name "*.php" -o -name "*.tpl" >scanfiles.input

if [ -f messages.po ] ; then
	JOIN="-j"
else
	JOIN=""
fi

xgettext -f scanfiles.input -o messages.po -L PHP -s --from-code=UTF-8 --copyright-holder="Thomas Krieger" --add-comments='$Id$' --no-wrap $JOIN

exit 0
