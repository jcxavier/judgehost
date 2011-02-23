/*
 * Error handling and logging functions
 *
 * $Id: lib.error.c 3209 2010-06-12 00:13:43Z eldering $
 *
 * Part of the DOMjudge Programming Contest Jury System and licenced
 * under the GNU GPL. See README and COPYING for details.
 */

/* Implementation details:
 *
 * All argument-list based functions call their va_list (v..) counterparts
 * to avoid doubled code. Furthermore, all functions use 'vlogmsg' to do the
 * actual writing of the logmessage and all error/warning functions use
 * 'vlogerror' to generate the error-logmessage from the input. vlogerror in
 * turn calls errorstring to generate the actual error message;
 */

#include "lib.error.h"

#include <stdlib.h>
#include <string.h>
#include <unistd.h>
#include <time.h>

/* For SYSLOG config variable */
#include "../etc/config.h"

/* Use program name in syslogging if defined */
#ifndef PROGRAM
#define PROGRAM NULL
#endif

const int exit_failure = -1;

/* Variables defining logmessages verbosity to stderr/logfile */
int  verbose      = LOG_NOTICE;
int  loglevel     = LOG_DEBUG;

/* Variables for tracking logging facilities */
FILE *stdlog      = NULL;
int  syslog_open  = 0;

/* Main function that contains logging code */
void vlogmsg(int msglevel, const char *mesg, va_list ap)
{
    time_t currtime;
    char timestring[128];
	char *buffer;
	int mesglen = (mesg==NULL ? 0 : strlen(mesg));
	int bufferlen;
	va_list aq;

	/* Try to open logfile if it is defined */
#ifdef LOGFILE
	if ( stdlog==NULL ) stdlog = fopen(LOGFILE,"a");
#endif

	/* Try to open syslog if it is defined */
#ifdef SYSLOG
	if ( ! syslog_open ) {
		openlog(PROGRAM, LOG_NDELAY | LOG_PID, SYSLOG);
		syslog_open = 1;
	}
#endif

	currtime = time(NULL);
	strftime(timestring, sizeof(timestring), "%b %d %H:%M:%S", localtime(&currtime));

	bufferlen = strlen(timestring)+strlen(progname)+mesglen+20;
	buffer = (char *)malloc(bufferlen);
	if ( buffer==NULL ) abort();

	snprintf(buffer, bufferlen, "[%s] %s[%d]: %s\n",
	         timestring, progname, getpid(), mesg);

	if ( msglevel<=verbose ) {
		va_copy(aq, ap);
		vfprintf(stderr, buffer, aq);
		fflush(stderr);
		va_end(aq);
	}
	if ( msglevel<=loglevel && stdlog!=NULL ) {
		va_copy(aq, ap);
		vfprintf(stdlog, buffer, aq);
		fflush(stdlog);
		va_end(aq);
	}

	free(buffer);

#ifdef SYSLOG
	if ( msglevel<=loglevel ) {
		buffer = vallocstr(mesg, ap);
		syslog(msglevel, "%s", buffer);
		free(buffer);
	}
#endif
}

/* Argument-list wrapper function around vlogmsg */
void logmsg(int msglevel, const char *mesg, ...)
{
	va_list ap;
	va_start(ap, mesg);

	vlogmsg(msglevel, mesg, ap);

	va_end(ap);
}

/* Function to generate error/warning string:
   - allocates memory for string (needs freeing later)
   - generates message of the form:
       errtype . ": " . mesg . ": " . errdescr
     where 'errtype' can be "WARNING" / "ERROR"
	 'mesg' is a program generated message (or NULL)
	 'errnum' is the last system call's errno (or zero)
*/
char *errorstring(const char *type, int errnum, const char *mesg)
{
	size_t buffersize;
	char *errtype, *errdescr, *buffer;

	/* Set errtype to given string or default to 'ERROR' */
	if ( type==NULL ) {
		errtype = strdup(ERRSTR);
		if ( errtype==NULL ) abort();
	} else {
		errtype = (char *)type;
	}

	errdescr = NULL;
	if ( errnum != 0 ) {
		errdescr = strerror(errno);
	} else if ( mesg == NULL ) {
		errdescr = strdup("unknown error");
	}

	buffersize = strlen(errtype)
	            + (errdescr == NULL ? 0 : strlen(errdescr))
	            + (mesg == NULL     ? 0 : strlen(mesg))
	            + 5;

	buffer = (char *)malloc(sizeof(char) * buffersize);
	if ( buffer==NULL ) abort();
	buffer[0] = '\0';

	strcat(buffer, errtype);
	strcat(buffer, ": ");

	if ( mesg != NULL )     strcat(buffer, mesg);

	if ( mesg != NULL &&
	     errdescr != NULL ) strcat(buffer, ": ");

	if ( errdescr != NULL )	strcat(buffer, errdescr);

	if ( type == NULL ) free(errtype);

	return buffer;
}

/* Function to generate and write error logmessage (using vlogmsg) */
void vlogerror(int errnum, const char *mesg, va_list ap)
{
	char *buffer;

	buffer = errorstring(ERRSTR, errnum, mesg);

	vlogmsg(LOG_ERR, buffer, ap);

	free(buffer);
}

/* Argument-list wrapper function around vlogerror */
void logerror(int errnum, const char *mesg, ...)
{
	va_list ap;
	va_start(ap, mesg);

	vlogerror(errnum, mesg, ap);

	va_end(ap);
}

/* Logs an error message and exit with non-zero exitcode */
void verror(int errnum, const char *mesg, va_list ap)
{
	vlogerror(errnum, mesg, ap);

	exit(exit_failure);
}

/* Argument-list wrapper function around verror */
void error(int errnum, const char *mesg, ...)
{
	va_list ap;
	va_start(ap, mesg);

	verror(errnum, mesg, ap);

	va_end(ap);
}

/* Logs a warning message */
void vwarning(int errnum, const char *mesg, va_list ap)
{
	char *buffer;

	buffer = errorstring(WARNSTR, errnum, mesg);

	vlogmsg(LOG_WARNING, buffer, ap);

	free(buffer);
}

/* Argument-list wrapper function around vwarning */
void warning(int errnum, const char *mesg, ...)
{
	va_list ap;
	va_start(ap, mesg);

	vwarning(errnum, mesg, ap);

	va_end(ap);
}

/* Allocates a string with variable arguments */
char *vallocstr(const char *mesg, va_list ap)
{
	char *str;
	char tmp[2];
	int len, n;
	va_list aq;

	va_copy(aq,ap);
	len = vsnprintf(tmp,1,mesg,aq);
	va_end(aq);

	if ( (str = (char *) malloc(len+1))==NULL ) error(errno,"allocating string");

	va_copy(aq,ap);
	n = vsnprintf(str,len+1,mesg,aq);
	va_end(aq);

	if ( n==-1 || n>len ) error(0,"cannot write all of string");

	return str;
}

/* Argument-list wrapper function around vallocstr */
char *allocstr(const char *mesg, ...)
{
	va_list ap;
	char *str;

	va_start(ap,mesg);
	str = vallocstr(mesg,ap);
	va_end(ap);

	return str;
}
