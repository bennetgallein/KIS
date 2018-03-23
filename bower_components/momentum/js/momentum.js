$('.collapseable-nav').click(function() {
    if ($(this).children().hasClass("mdi-plus")) {
      $(this).children().removeClass("mdi-plus");
      $(this).children().addClass("mdi-minus");
    } else {
      $(this).children().removeClass("mdi-minus");
      $(this).children().addClass("mdi-plus");
    }
});
new Morris.Line({
  // ID of the element in which to draw the chart.
  element: 'morrislinechart',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    { year: '2008', value1: 20, value2: 19, value3: 18, value4: 17, value5: 16, value6: 15, value7: 14},
    { year: '2009', value1: 10, value2: 9, value3: 8, value4: 7, value5: 6, value6: 5, value7: 4 },
    { year: '2010', value1: 5, value2: 4, value3: 3, value4: 2, value5: 1, value6: 0, value7: -1 },
    { year: '2011', value1: 5, value2: 4, value3: 3, value4: 2, value5: 1, value6: 0, value7: -1 },
    { year: '2012', value1: 20, value2: 19, value3: 18, value4: 17, value5: 16, value6: 15, value7: 14 }
  ],
  // The name of the data record attribute that contains x-values.
  xkey: 'year',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['value1', 'value2', 'value3', 'value4', 'value5', 'value6', 'value7'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['Value', 'Value2', 'Value3', 'Value4', 'Value5', 'Value6', 'Value7'],
  hideHover: "auto"
});
new Morris.Area({
  // ID of the element in which to draw the chart.
  element: 'morrisareachart',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
    { year: '2008', value1: 20, value2: 19, value3: 18, value4: 17, value5: 16, value6: 15, value7: 14},
    { year: '2009', value1: 10, value2: 9, value3: 8, value4: 7, value5: 6, value6: 5, value7: 4 },
    { year: '2010', value1: 5, value2: 4, value3: 3, value4: 2, value5: 1, value6: 0, value7: -1 },
    { year: '2011', value1: 5, value2: 4, value3: 3, value4: 2, value5: 1, value6: 0, value7: -1 },
    { year: '2012', value1: 20, value2: 19, value3: 18, value4: 17, value5: 16, value6: 15, value7: 14 }
  ],
  // The name of the data record attribute that contains x-values.
  xkey: 'year',
  // A list of names of data record attributes that contain y-values.
  ykeys: ['value1', 'value2', 'value3', 'value4', 'value5', 'value6', 'value7'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['Value', 'Value2', 'Value3', 'Value4', 'Value5', 'Value6', 'Value7'],
  hideHover: "auto"
});
Morris.Donut({
  element: 'morrispiechart',
  data: [
    {label: "Friends", value: 30},
    {label: "Allies", value: 15},
    {label: "Enemies", value: 45},
    {label: "Neutral", value: 10}
  ]
});
