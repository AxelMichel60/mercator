import { Chart, BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend } from "chart.js"
import ChartDataLabels from "chartjs-plugin-datalabels"
import {TreemapController, TreemapElement} from 'chartjs-chart-treemap';
import GaugeController from './gauge-controller';
import { color } from "chart.js/helpers";

Chart.register(GaugeController);
Chart.register(BarController, BarElement, CategoryScale, LinearScale, Tooltip, Legend, ChartDataLabels, TreemapController, TreemapElement);

// ===========================================================================

function createGaugeChart(ctx, value) {
    return new Chart(ctx, {
      type: 'gauge',
      data: {
        datasets: [{
          value: value,
          data: [40, 80,100],
          backgroundColor: ['#E15759', '#F28E2B', '#59A14F'],
        }]
      },
      options: {
        needle: {
          radiusPercentage: 2,
          widthPercentage: 3.2,
          lengthPercentage: 80,
          color: 'rgba(0, 0, 0, 1)'
        },
        valueLabel: {
          display: true,
          formatter: (value) => {
              return value;
            },
          color: 'rgba(255, 255, 255, 1)',
          backgroundColor: 'rgba(0, 0, 0, 1)',
          borderRadius: 5,
          padding: {
            top: 10,
            bottom: 10
          }
        },
        plugins: {
          datalabels: {
            formatter: function(value, context) {
              return null;
            }
          }
        }
      }
    });
}

//===========================================================================

const customColors = [
         "#1F77B4", "#AEC7E8", "#FF7F0E", "#FFBB78",
         "#2CA02C", "#98DF8A", "#D62728", "#FF9896",
         "#9467BD", "#C5B0D5", "#8C5694", "#C49C94",
         "#E377C2", "#F7B6D2", "#7F7F7F", "#C7C7C7",
         "#BCBD22", "#DBDB8D", "#17BECF", "#9EDAE5" ];

const barChartConfig = {
  type: "bar",
  data: window.chartData.barChart,
  options: {
    responsive: true,
    maintainAspectRatio: false,
    animation: { duration: 600 },

    // 📌 Gestion du curseur au survol
    onHover: (event, elements) => {
      const chartDiv = document.getElementById("bar_chart_div");
      chartDiv.style.cursor = elements.length ? "pointer" : "default";
    },

    // Tooltips dans Chart.js 4
    plugins: {
      tooltip: {
        callbacks: {
          title: function () {
            return null;
          },
          label: function (tooltipItem) {
            const dataset = tooltipItem.dataset;
            return `${dataset.label}: ${dataset.value}`;
          },
        },
      },

      datalabels: {
        color: "white",
        font: {
          weight: "bold",
        },
        formatter: function (value, context) {
          return value > 1 ? context.dataset.label : "";
        },
      },
      legend: {
        display: false,
      },
    },

    // Échelle des axes en Chart.js 4
    scales: {
      x: {
        stacked: true,
      },
      y: {
        beginAtZero: true,
        stacked: true,
        ticks: {
          stepSize: 5,
          max: 100,
        },
      },
    },

    // Gestion des clics sur le graphique
    onClick: (event, elements) => {
      if (elements.length > 0) {
        const elementIndex = elements[0].datasetIndex;
        window.location = window.chartData.barChart.datasets[elementIndex].url;
      }
    },

    // Callback d'affichage terminé
    onComplete: function () {
      const ctx = this.ctx;
      ctx.font = Chart.defaults.font.family;
      ctx.textAlign = "left";
      ctx.textBaseline = "bottom";
    },
  },
};

//===========================================================================

function createBarChart(ctx, config) {
    // Assign colors
    for (let j = 0; j < window.chartData.barChart.datasets.length; j++) {
        window.chartData.barChart.datasets[j].backgroundColor = customColors[j % customColors.length];
    }

    // Normalize data (%)
    for (let i = 0; i < (window.chartData.barChart.labels.length); i++) {
      var sum=0;
      for (let j = 0; j < window.chartData.barChart.datasets.length; j++)
        sum += window.chartData.barChart.datasets[j].data[i];
      if (sum>0) {
      for (let j = 0; j < window.chartData.barChart.datasets.length; j++)
        window.chartData.barChart.datasets[j].data[i] =
          Math.floor(window.chartData.barChart.datasets[j].data[i]*1000/sum)/10;
        }
      }
      window.barchart=new Chart(ctx, config);
}

//===========================================================================

var treeMapConfig = {
  type: "treemap",
  data: {
    datasets: [{
      tree: window.topTags,
      key: "num",
      groups: ['group','tag'],
      datalabels: {
        color: function (ctx) {
          var item = ctx.dataset.data[ctx.dataIndex];
          if (item.l==1)
              return "white";
          else
            return null;
        },
        font: {
          weight: "bold",
        },
          formatter: function (value, context) {
            return value._data.label;
          },
        },
      spacing: 0.5,
      borderWidth: 1.5,
      fontColor: "white",

      fontColor: function (ctx) {
        var item = ctx.dataset.data[ctx.dataIndex];
        switch (item.l) {
          case 0:return "#222";
          case 1:return "#FFF";
          default:return "#000";
        }
      },
      font: function (ctx) {
        var item = ctx.dataset.data[ctx.dataIndex];
        if (item.l==1)
            return { color: "white"};
        else
          return null;
      },
      borderColor: "grey",

      backgroundColor: function (ctx) {
        var item = ctx.dataset.data[ctx.dataIndex];
        if (!item) return;
        switch (item.l) {
          case 0: return "#FFF";
          case 1: return customColors[ctx.dataIndex%20];
          default: return "#000";
          }
        },
    }]
  },
  options: {
    responsive: true,
    plugins: {
        title: { display: true },
        legend: { display: false },
        tooltip: { enabled: false },
    },
    maintainAspectRatio: false,
    title: { display: false},
    animation: { duration: 600 },

    onClick: function (event, active) {
      var chart = this;
      for (let i = 0; i < active.length; i++) {
        const item = active[i];
        var data = this.data.datasets[item.datasetIndex].data[item.index];
        if (data._data.children.length === 1) {
          window.location=data._data.children[0].url;
        }
      }
    },
    onHover: (event, elements) => {
        const chartCanvas = event.native.target;
        chartCanvas.style.cursor = elements.length ? "pointer" : "default";
        },
    }
};

function createTreeMap(ctx, config) {
      window.treeMapChart=new Chart(ctx, config);
    }

//===========================================================================

// Exécuter après le chargement du DOM
document.addEventListener('DOMContentLoaded', () => {
    if (window.chartData) {
        createGaugeChart(document.getElementById('gauge_chart1_div'), window.chartData.maturity1);
        createGaugeChart(document.getElementById('gauge_chart2_div'), window.chartData.maturity2);
        createGaugeChart(document.getElementById('gauge_chart3_div'), window.chartData.maturity3);

        createBarChart(document.getElementById('bar_chart_div').getContext('2d'), barChartConfig);

        createTreeMap(document.getElementById("treemap_chart_div").getContext("2d"), treeMapConfig);

    }
});
