#!/bin/sh

# SQL compile wrapper-script for 'compile.sh'.
# See that script for syntax and more info.

SOURCE="$1"
DEST="$2"

cp -f $SOURCE $DEST

# Check for '#!' interpreter line: don't allow it to prevent teams
# from passing options to the interpreter.
if grep '^#!' $SOURCE >/dev/null 2>&1 ; then
	echo "Error: interpreter statement(s) found:"
	grep -n '^#!' $SOURCE
	exit 1
fi


# Write executing script:
#cat > $DEST <<EOF
#!/bin/sh
# Generated shell-script to execute awk interpreter on source.

# exec awk -f $SOURCE
#EOF

#chmod a+x $DEST

exit 0