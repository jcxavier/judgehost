# Variables (mostly paths) set by configure.
# This file is globally included via Makefile.global.

# General package variables
PACKAGE = domjudge
VERSION = 3.1.3
DISTNAME = $(PACKAGE)-$(VERSION)

PACKAGE_NAME      = DOMjudge
PACKAGE_VERSION   = 3.1.3
PACKAGE_STRING    = DOMjudge 3.1.3
PACKAGE_TARNAME   = domjudge
PACKAGE_BUGREPORT = domjudge-devel@lists.A-Eskwadraat.nl

# Compilers and FLAGS
CC  = gcc
CXX = g++
CPP = gcc -E

CFLAGS   = -g -O2 -Wall -fstack-protector -fPIE
CXXFLAGS = -g -O2 -Wall -fstack-protector -fPIE
CPPFLAGS = 
LDFLAGS  =  -pie 

EXEEXT = 
OBJEXT = .o

# Submit protocols
SUBMIT_DEFAULT    = 2
SUBMIT_ENABLE_CMD = 0
SUBMIT_ENABLE_WEB = 1

# libmagic
LIBMAGIC = 

# libcURL
CURL_CFLAGS = 
CURL_LIBS   = -lcurl
CURL_STATIC = /usr/lib/libcurl.a -lidn -llber -lldap -lrt -lgssapi_krb5 -lz -lgnutls -lgcrypt

# libboost
BOOST_CPPFLAGS  = -I/usr/include
BOOST_LDFLAGS   = -L/usr/lib64
BOOST_REGEX_LIB = -lboost_regex-mt

# libgmpxx
LIBGMPXX = -lgmp -lgmpxx

# htpasswd
HTPASSWD = htpasswd

# User:group file ownership of password files
DOMJUDGE_USER   = root
WEBSERVER_GROUP = www-data

# Autoconf prefixes and paths
fhs_enabled    = 
prefix         = /home/domjudge-run//domjudge
exec_prefix    = ${prefix}

bindir         = ${exec_prefix}/bin
sbindir        = ${exec_prefix}/sbin
libexecdir     = ${exec_prefix}/libexec
sysconfdir     = ${prefix}/etc
sharedstatedir = ${prefix}/com
localstatedir  = ${prefix}/var
libdir         = ${exec_prefix}/lib
includedir     = ${prefix}/include
oldincludedir  = /usr/include
datarootdir    = ${prefix}/share
datadir        = ${datarootdir}
infodir        = ${datarootdir}/info
localedir      = ${datarootdir}/locale
mandir         = ${datarootdir}/man
docdir         = ${datarootdir}/doc/${PACKAGE_TARNAME}
htmldir        = ${docdir}
dvidir         = ${docdir}
pdfdir         = ${docdir}
psdir          = ${docdir}

# Installation paths
domserver_bindir       = /home/domjudge-run//domjudge/domserver/bin
domserver_etcdir       = /home/domjudge-run//domjudge/domserver/etc
domserver_wwwdir       = /home/domjudge-run//domjudge/domserver/www
domserver_sqldir       = /home/domjudge-run//domjudge/domserver/sql
domserver_libdir       = /home/domjudge-run//domjudge/domserver/lib
domserver_libwwwdir    = /home/domjudge-run//domjudge/domserver/lib/www
domserver_libsubmitdir = /home/domjudge-run//domjudge/domserver/lib/submit
domserver_logdir       = /home/domjudge-run//domjudge/domserver/log
domserver_tmpdir       = /home/domjudge-run//domjudge/domserver/tmp
domserver_submitdir    = /home/domjudge-run//domjudge/domserver/submissions

judgehost_bindir       = /home/domjudge-run//domjudge/judgehost/bin
judgehost_etcdir       = /home/domjudge-run//domjudge/judgehost/etc
judgehost_libdir       = /home/domjudge-run//domjudge/judgehost/lib
judgehost_libjudgedir  = /home/domjudge-run//domjudge/judgehost/lib/judge
judgehost_logdir       = /home/domjudge-run//domjudge/judgehost/log
judgehost_tmpdir       = /home/domjudge-run//domjudge/judgehost/tmp
judgehost_judgedir     = /home/domjudge-run//domjudge/judgehost/judgings

domjudge_docdir        = /home/domjudge-run//domjudge/doc

domserver_dirs = $(domserver_bindir) $(domserver_etcdir) $(domserver_wwwdir) \
                 $(domserver_libdir) $(domserver_libsubmitdir) \
                 $(domserver_libwwwdir) $(domserver_logdir) \
                 $(domserver_tmpdir) $(domserver_submitdir) $(domserver_sqldir)/upgrade \
                 $(addprefix $(domserver_wwwdir)/images/,affiliations countries teams) \
                 $(addprefix $(domserver_wwwdir)/,public team jury plugin js)

judgehost_dirs = $(judgehost_bindir) $(judgehost_etcdir) $(judgehost_libdir) \
                 $(judgehost_libjudgedir) $(judgehost_logdir) \
                 $(judgehost_tmpdir) $(judgehost_judgedir)

docs_dirs      = $(addprefix $(domjudge_docdir)/,admin judge team examples logos)

# Macro to substitute configure variables.
# Defined in makefile to allow for expansion of ${prefix} etc. during
# build and install, conforming to the GNU coding standards, see:
# http://www.gnu.org/software/hello/manual/autoconf/Installation-Directory-Variables.html
define substconfigvars
@[ -n "$(QUIET)" ] || echo "Substituting configure variables in '$@'."
@cat $< | sed \
	-e "s|@configure_input[@]|Generated from '$<' on `date`.|g" \
	-e 's,@DOMJUDGE_VERSION[@],3.1.3,g' \
	-e 's,@domserver_bindir[@],/home/domjudge-run//domjudge/domserver/bin,g' \
	-e 's,@domserver_etcdir[@],/home/domjudge-run//domjudge/domserver/etc,g' \
	-e 's,@domserver_wwwdir[@],/home/domjudge-run//domjudge/domserver/www,g' \
	-e 's,@domserver_sqldir[@],/home/domjudge-run//domjudge/domserver/sql,g' \
	-e 's,@domserver_libdir[@],/home/domjudge-run//domjudge/domserver/lib,g' \
	-e 's,@domserver_libwwwdir[@],/home/domjudge-run//domjudge/domserver/lib/www,g' \
	-e 's,@domserver_libsubmitdir[@],/home/domjudge-run//domjudge/domserver/lib/submit,g' \
	-e 's,@domserver_logdir[@],/home/domjudge-run//domjudge/domserver/log,g' \
	-e 's,@domserver_tmpdir[@],/home/domjudge-run//domjudge/domserver/tmp,g' \
	-e 's,@domserver_submitdir[@],/home/domjudge-run//domjudge/domserver/submissions,g' \
	-e 's,@judgehost_bindir[@],/home/domjudge-run//domjudge/judgehost/bin,g' \
	-e 's,@judgehost_etcdir[@],/home/domjudge-run//domjudge/judgehost/etc,g' \
	-e 's,@judgehost_libdir[@],/home/domjudge-run//domjudge/judgehost/lib,g' \
	-e 's,@judgehost_libjudgedir[@],/home/domjudge-run//domjudge/judgehost/lib/judge,g' \
	-e 's,@judgehost_logdir[@],/home/domjudge-run//domjudge/judgehost/log,g' \
	-e 's,@judgehost_tmpdir[@],/home/domjudge-run//domjudge/judgehost/tmp,g' \
	-e 's,@judgehost_judgedir[@],/home/domjudge-run//domjudge/judgehost/judgings,g' \
	-e 's,@domjudge_docdir[@],/home/domjudge-run//domjudge/doc,g' \
	-e 's,@DOMJUDGE_USER[@],root,g' \
	-e 's,@WEBSERVER_GROUP[@],www-data,g' \
	-e 's,@BEEP[@],@BEEP@,g' \
	-e 's,@RUNUSER[@],domjudge-run,g' \
	-e 's,@CHROOTDIR[@],/home/domjudge-run//domjudge/judgehost/judgings,g' \
	-e 's,@SUBMIT_DEFAULT[@],2,g' \
	-e 's,@SUBMIT_ENABLE_CMD[@],0,g' \
	-e 's,@SUBMIT_ENABLE_WEB[@],1,g' \
	> $@
endef
