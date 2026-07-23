<div class="card mt-4 shadow-sm">

    <div class="card-header bg-danger text-white">

        <h5 class="mb-0">
            📊 Top 10 Highest Risk Score
        </h5>

    </div>

    <div class="card-body">

        <canvas id="topRiskChart" height="120"></canvas>

    </div>

</div>

<script>

fetch("{{ url('/api/risk') }}")

.then(res=>res.json())

.then(data=>{

    new Chart(document.getElementById('topRiskChart'),{

        type:'bar',

        data:{

            labels:data.map(item=>item.country.name),

            datasets:[{

                label:'Risk Score',

                data:data.map(item=>item.total_score),

                backgroundColor:'#dc3545'

            }]

        },

        options:{

            indexAxis:'y',

            responsive:true,

            plugins:{

                legend:{

                    display:false

                }

            }

        }

    });

});

</script>
