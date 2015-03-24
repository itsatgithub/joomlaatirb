Para comenzar el desarrollo:
1.- El directorio 'build' se crea automáticamente, y no debe de estar en subversion

Para desarrollar:
1.- Se desarrolla en joomlaatirb, y se suben los cambios a svn
2.- Se testea durante el desarrollo en joomlaatirb
3.- Para pasar los cambios a test, se ejecuta phing desde workspace/com_irbtools

In order to use dbdeploy in remote servers, take care of:
	.- the remote firewall
	.- the remote MySQL server user, who need to have remote permissions.