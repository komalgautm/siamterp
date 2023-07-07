/*Number of Box*/

let numOfBox;

if(itfUnit=='5')
{
	numOfBox=itfQty;
}
else if(itfUnit=='2')
{
	numOfBox=itfQty/itfEanQty;
}
else if(itfUnit=='1')
{
	numOfBox=itfQty/ean_pp_itf;
}
else
{
	numOfBox=0;
}

/* Number of Box end */

/*Unit price  */
//=IF(itfUnit="5",itfCalSelling,IF(itfUnit="2",itfCalSelling/itfEanQty,IF(itfUnit="1",itfCalSelling/ean_pp_itf,"ERROR")))

let unitPrice;

if(itfUnit=='5')
{
	unitPrice=itfCalSelling;
}
else if(itfUnit=='2')
{
	unitPrice=itfCalSelling/itfEanQty;
}
else if(itfUnit=='1')
{
	unitPrice=itfCalSelling/ean_pp_itf;
}
else
{
	unitPrice=0;
}
/*Unit price end  */