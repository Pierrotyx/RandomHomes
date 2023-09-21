<div class="info-box">
  <div class="filter-wrapper">
    <div style="calculator">
      <h1>Mortgage Calculator {{!empty( $current ) ? $current->state : ''}}</h1>
      <select class="calculatorFilters" onchange="changeState(this.value)">
        <option value="">Choose a state...</option>
        <?php
        $avgHome = !empty( $current ) ? $current->avgHousePrice : 400000;
        $currentState = !empty( $current ) ? $current->state : '';
        foreach( $states as $state )
        {
          ?>
          <option value="<?= $state->state; ?>" <?= $state->state == $currentState ? 'selected' : ''; ?>><?= $state->state; ?></option>
          <?php
        }
        ?>
      </select>
      <hr>
      <div class="calculatorFilters">
        <div>
          <label for="loanAmount">Property Price ($):</label>
          <input
            type="text"
            id="loanAmount"
            placeholder="Enter loan amount"
            onkeyup="changeFilters(this); calculateMortgage();"
            oninput="formatNumberInput(this)"
            value="{{ !empty( $home ) ? number_format( $home->price ) : number_format( $avgHome )}}"
          >
        </div>
        <div>
          <label for="downPayment">Down Payment ($):</label>
          <input
            type="text"
            id="downPayment"
            placeholder="Enter down payment"
            onkeyup="down = true; calculateMortgage()"
            oninput="formatNumberInput(this)"
            value="{{ !empty( $home ) ? number_format( $home->price / 5 ) : number_format( $avgHome / 5 )}}"
          />
        </div>
      </div>
      <div class="calculatorFilters">
        <div>
          <label for="interestRate">Interest Rate (%):</label>
          <input
            type="number"
            max="50"
            id="interestRate"
            placeholder="Enter interest rate"
            onkeyup="calculateMortgage()"
            step=".1"
            value="7.5"
          >
        </div>
        <div>
          <label for="loanTerm">Loan Term (years):</label>
          <select id="loanTerm" onchange="changeIntrest( this.value ); calculateMortgage();">
            <option value="30">30 years Fixed</option>
            <option value="20">20 years Fixed</option>
            <option value="15">15 years Fixed</option>
            <option value="10">10 years Fixed</option>
          </select>
        </div>
      </div>
      <div class="calculatorFilters">
        <div>
          <label for="propertyTax">Property Tax (%):</label>
          <input
            type="number"
            max="50"
            id="propertyTax"
            placeholder="Enter Property Tax"
            onkeyup="calculateMortgage()"
            step=".1"
            value="{{!empty( $current ) ? $current->propertyTax : '.99'}}"
          >
        </div>
        <div>
          <label for="homeInsurance">Insurance ($/year):</label>
          <input
            type="text"
            id="homeInsurance"
            placeholder="Enter Home Insurance"
            onkeyup="homeInsurance = true; calculateMortgage()"
            oninput="formatNumberInput(this)"
            value="{{ !empty( $home ) ? number_format( $home->price * .0042 ) : number_format( $avgHome * .0042 )}}"
          />
        </div>
      </div>
      <div class="calculatorFilters">
        <div>
          <label for="hoa">HOA Cost ($/year):</label>
          <input
            type="text"
            id="hoa"
            placeholder="Enter HOA Cost per Year"
            onkeyup="calculateMortgage();"
            oninput="formatNumberInput(this)"
            value="0"
          >
        </div>
        <div>
          <label for="utility">
            <input type="checkbox" id="includeUtil" onChange="calculateMortgage()" checked/>Utilities ($):
          </label>
          <input
            type="text"
            id="utility"
            placeholder="Enter Utility Cost"
            onkeyup="utility = true; calculateMortgage()"
            oninput="formatNumberInput(this)"
            value="{{ !empty( $home ) ? number_format( $home->price / 880 ) : number_format( $avgHome / 880 )}}"
          />
        </div>
      </div>
      
      <div id="caclResult">
        <div class="estimatedMonth">
          <h2 class="calcCost">
            <span>Estimated Monthly Cost</span>
            <br>
            <span><span id="monthlyCost"></span></span>
          </h2>
          <div class="calcCost">
              <span>Salary:</span>
              <br>
              <span><span id="salaryLow"></span> - <span id="salaryHigh"></span></span>
          </div>
          <div class="calcCost">
              <span>Hourly:</span>
              <br>
              <span><span id="hourlyLow"></span> - <span id="hourlyHigh"></span></span>
          </div>
        </div>
        <br>
        <div class="estimatedInfo">
          <h2 class="calcCost">Extra Info</h2>
          <div class="result-item">
            <div>
              <span>Yearly Cost:</span>
              <br>
              <span><span id="yearlyCost"></span></span>
            </div>
            <div>
              <span>Principal:</span>
              <br>
              <span><span id="monthlyIntrest"></span></span>
            </div>
          </div>
          <div class="result-item">
            <div>
              <span>Property Tax:</span>
              <br>
              <span><span id="monthlyProperty"></span></span>
            </div>
            <div>
              <span>Home Insurance:</span>
              <br>
              <span><span id="monthlyInsurance"></span></span>
            </div>
          </div>
          <div class="result-item">
            <div>
              <span>Monthly HOA:</span>
              <br>
              <span><span id="monthlyHoa"></span></span>
            </div>
          </div>
        </div>
      </div>
      
      <script>
        var down = false;
        var utility = false;
        var homeInsurance = false;
        $( document ).ready( calculateMortgage() );
        function calculateMortgage() {
          const loanAmount   = parseFloat($("#loanAmount").val().replace(/,/g, '') || 0);
          const downPayment  = parseFloat($("#downPayment").val().replace(/,/g, '') || 0);
          const loan         = Math.max( loanAmount - downPayment, 0 );
          const interestRate = parseFloat($("#interestRate").val() || 0) / 100;
          const loanTerm     = parseInt($("#loanTerm").val().replace(/,/g, '') || 0);
          const hoaCost      = parseFloat($("#hoa").val().replace(/,/g, '') || 0);
          const insurance    = parseFloat($("#homeInsurance").val().replace(/,/g, '') || 0);
          const propertyTax  = parseFloat($("#propertyTax").val() || 0) / 100;
          var utilities      = parseFloat($("#utility").val().replace(/,/g, '') || 0);
          if( !$('#includeUtil').is(':checked') )
          {
            utilities = 0;
          }
          
          const monthlyInterestRate = interestRate / 12;
          const monthlyHoa          = hoaCost / 12;
          const monthlyInsurance    = insurance / 12;
          const monthlyPropertyTax  = propertyTax / 12 * loan;
          const monthylInstrest     = loan * ((monthlyInterestRate / (1 - Math.pow(1 + monthlyInterestRate, -loanTerm * 12))) || ( 1 / ( loanTerm * 12 ) ) )
          const monthlyPayment      = monthylInstrest + monthlyHoa + monthlyInsurance + monthlyPropertyTax + utilities;
          const yearlyPayment       = monthlyPayment * 12;
          const salaryLow           = yearlyPayment * 2.5;
          const salaryHigh          = yearlyPayment * 3.57;
          const hourlyLow           = salaryLow / 2080;
          const hourlyHigh          = salaryHigh / 2080;
          
          document.getElementById('monthlyCost').textContent        = '$' + monthlyPayment.toLocaleString('en-US', { maximumFractionDigits: 0 });
          document.getElementById('yearlyCost').textContent         = '$' + yearlyPayment.toLocaleString('en-US', { maximumFractionDigits: 0 });
          document.getElementById('monthlyIntrest').textContent     = '$' + monthylInstrest.toLocaleString('en-US', { maximumFractionDigits: 0 });
          document.getElementById('monthlyProperty').textContent    = '$' + monthlyPropertyTax.toLocaleString('en-US', { maximumFractionDigits: 0 });
          document.getElementById('monthlyInsurance').textContent   = '$' + monthlyInsurance.toLocaleString('en-US', { maximumFractionDigits: 0 });
          document.getElementById('monthlyHoa').textContent         = '$' + monthlyHoa .toLocaleString('en-US', { maximumFractionDigits: 0 });
          document.getElementById('salaryLow').textContent          = '$' + salaryLow.toLocaleString('en-US', { maximumFractionDigits: 0 }) + ' (40%)';
          document.getElementById('salaryHigh').textContent         = '$' + salaryHigh.toLocaleString('en-US', { maximumFractionDigits: 0 }) + ' (28%)';
          document.getElementById('hourlyLow').textContent          = '$' + hourlyLow.toLocaleString('en-US', { maximumFractionDigits: 2 }) + ' (40%)';
          document.getElementById('hourlyHigh').textContent         = '$' + hourlyHigh.toLocaleString('en-US', { maximumFractionDigits: 2 }) + ' (28%)';
        }
        
        function changeIntrest( value )
        {
            if( value == 30 || value == 20 )
            {
              $('#interestRate').val( 7.5 );
            }
            else if( value == 15 )
            {
              $('#interestRate').val( 6.8 );
            }
            else if( value == 10 )
            {
              $('#interestRate').val( 6.7 );
            }
        }

        function changeState( location )
        {
          $("#overlay").show();
          if( location.length > 0 )
          {
            window.location.href = '/calculator/' + location.replace(' ', '-').toLowerCase() + window.location.search;
          }
          else
          {
            window.location.href = '/calculator' + window.location.search;
          }
        }

        function changeFilters( e )
        {
          var value = parseFloat( $( e ).val().replace(/,/g, '') );
          if( !down )
          {
            var newValue = value / 5;
            $('#downPayment').val( newValue.toLocaleString('en-US') );
          }

          if( !utility )
          {
            var newValue = value / 880;
            $('#utility').val( newValue.toLocaleString('en-US',{ minimumFractionDigits: 0, maximumFractionDigits: 0 }) );
          }

          if( !homeInsurance )
          {
            var newValue = value * .0042;
            $('#homeInsurance').val( newValue.toLocaleString('en-US',{ minimumFractionDigits: 0, maximumFractionDigits: 0 }) );
          }
        }

      </script>
    </div>
  </div>
</div>