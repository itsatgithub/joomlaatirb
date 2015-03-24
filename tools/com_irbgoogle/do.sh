#!/bin/sh

# item name and other variables
ITEM=com_irbgoogle
NAME=irbgoogle
HOME=/home/roberto/workspace/joomlaatirb

# delete the zip file if exists
if [ -f $ITEM.zip ]
then
	rm $ITEM.zip
fi

# copy the xml file to the current repository
cp $HOME/administrator/components/$ITEM/$NAME.xml .

# create the repository and copy the administrator files
if [ ! -d ./administrator/components ]
then
    mkdir ./administrator
    mkdir ./administrator/components
fi
cp -R $HOME/administrator/components/$ITEM ./administrator/components/

# create the repository and copy the components files
if [ ! -d ./components ]
then
    mkdir ./components
fi
cp -R $HOME/components/$ITEM ./components/

# create the repository and copy the administrator language files
if [ ! -d ./administrator/language/en-GB ]
then
    mkdir ./administrator/language
    mkdir ./administrator/language/en-GB
fi
cp $HOME/administrator/language/en-GB/en-GB.$ITEM.ini ./administrator/language/en-GB/
cp $HOME/administrator/language/en-GB/en-GB.$ITEM.menu.ini ./administrator/language/en-GB/

# create the repository and copy the language file
if [ ! -d ./language/en-GB ]
then
    mkdir ./language
    mkdir ./language/en-GB
fi
cp $HOME/language/en-GB/en-GB.$ITEM.ini ./language/en-GB/

# create the zip file
zip -r $ITEM.zip $NAME.xml administrator/components/$ITEM components/$ITEM administrator/language/en-GB/en-GB.$ITEM.menu.ini administrator/language/en-GB/en-GB.$ITEM.ini language/en-GB/en-GB.$ITEM.ini -x *.svn*

 