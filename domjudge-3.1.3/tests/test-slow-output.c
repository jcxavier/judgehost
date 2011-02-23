/* $Id: test-slow-output.c 3302 2010-08-09 18:39:20Z eldering $
 *
 * Output answers slowly, should give TIMELIMIT with some output
 * However it doesn't give output: GNU C seems not to flush buffers
 * when it receives a signal.
 *
 * @EXPECTED_RESULTS@: TIMELIMIT,WRONG-ANSWER
 */

#include <stdio.h>

int main()
{
	int i,j,k,l,x;

	for(i=10; 1; i+=10) {
		x = 0;
		for(j=0; j<i; j++)
			for(k=0; k<i; k++)
				for(l=0; l<i; l++)
					x++;

		printf("%d ^ 3 = %d\n",i,x);
//		fflush(NULL);
	}

	return 0;
}
