<div class="card shadow-sm mb-4">

    <div class="card-header bg-primary text-white">

        <h5 class="mb-0">🔍 Filter Dashboard</h5>

    </div>

    <div class="card-body">

        <div class="row">

            <div class="col-md-4">

                <input
                    type="text"
                    id="searchCountry"
                    class="form-control"
                    placeholder="Cari Negara">

            </div>

            <div class="col-md-3">

                <select id="regionFilter" class="form-select">

                    <option value="">Semua Region</option>

                    <option>Africa</option>

                    <option>Americas</option>

                    <option>Asia</option>

                    <option>Europe</option>

                    <option>Oceania</option>

                </select>

            </div>

            <div class="col-md-3">

                <select id="riskFilter" class="form-select">

                    <option value="">Semua Risk</option>

                    <option>High</option>

                    <option>Medium</option>

                    <option>Low</option>

                </select>

            </div>

            <div class="col-md-2">

                <button
                    id="resetFilter"
                    class="btn btn-danger w-100">

                    Reset

                </button>

            </div>

        </div>

    </div>

</div>