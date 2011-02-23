/* $Id: test-sigsegv.c 3302 2010-08-09 18:39:20Z eldering $
 *
 * This should fail with RUN-ERROR due to a segmentation fault,
 * giving an exitcode 139.
 *
 * @EXPECTED_RESULTS@: RUN-ERROR
 */

int main()
{
	int a[10];
	int *b;

	b = 10;
	*b = a[-1000000];

	return 0;
}
