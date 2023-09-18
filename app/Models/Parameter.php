<?php

namespace App\Models;

class Parameter
{
    /**
	 *  @var DB $state the state information from the database.
     * This will be used to help with the randomizer
	 */
	public $state = null;

    /**
	 *  @var string $statusType The type of property the user is looking for...
     * It is only ForSale and ForRent
	 */
	public $statusType = null;

    /**
	 *  @var string $location is the location to look for homes.
	 */
	public $location = null;

    /**
	 *  @var int $minSalePrice the minimum price for buying property.
	 */
	public $minPrice = null;

    /**
	 *  @var int $maxSalePrice the maximum price for buying property.
	 */
	public $maxPrice = null;
    
    /**
	 *  @var int $rentMinPrice the minimum price for renting property.
	 */
	public $rentMinPrice = null;

    /**
	 *  @var int $rentMaxPrice the maximum price for renting property.
	 */
	public $rentMaxPrice = null;
    
    /**
	 *  @var int $bathsMin the minimum number of baths.
	 */
	public $bathsMin = null;

    /**
	 *  @var int $bathsMax the maximum number of baths.
	 */
	public $bathsMax = null;

    /**
	 *  @var int $bedsMin the minimum number of beds.
	 */
	public $bedsMin = null;

    /**
	 *  @var int $bedsMax the maximum number of beds.
	 */
	public $bedsMax = null;
    
     /**
	 *  @var int $livingMin the minimum size of living area in sqft.
	 */
	public $livingMin = null;

    /**
	 *  @var int $livingMax the maximum size of living area in sqft.
	 */
	public $livingMax = null;

    /**
	 *  @var int $lotMin the minimum size of lot area in sqft.
	 */
	public $lotMin = null;

    /**
	 *  @var int $lotMax the minimum size of lot area in sqft.
	 */
	public $lotMax = null;

    /**
	 *  @var int $rentMaxPrice the maximum price for renting property.
	 */
	public $homeType = [];
    
    /**
	 *  @var bool $hasCity checks if user is searching with a city.
	 */
	public $hasCity = false;

    /**
	 *  When declaring object, must give the property status and the state object.
	 */
    function __construct( $type, $state, $city )
    {
        $this->state = $state;
        $this->statusType = $type;
        $this->hasCity = !empty( $city );
        $this->location = ( $this->hasCity ? ( $city . ', ' ) : '' ) . $state->stateCode;
        $intList = ['minrp', 'maxrp', 'minsp', 'maxsp'];
    }

    public function run()
    {
        $parametersList = [
            'status_type'  => $this->statusType,
            'location'     => $this->location,
            'minPrice'     => $this->minPrice,
            'maxPrice'     => $this->maxPrice,
            'rentMinPrice' => $this->rentMinPrice,
            'rentMaxPrice' => $this->rentMaxPrice,
            'home_type'    => $this->homeType,
            'bathsMin'     => $this->bathsMin,
            'bathsMax'     => $this->bathsMax,
            'bedsMin'      => $this->bedsMin,
            'bedsMax'      => $this->bedsMax,
            'sqftMin'      => $this->livingMin,
            'sqftMax'      => $this->livingMax,
            'lotSizeMin'   => $this->lotMin,
            'lotSizeMax'   => $this->lotMax,
        ];

        $parameters = [];
        foreach( $parametersList as $key => $parameter )
        {
            if( $parameter )
            {
                $parameters[ $key ] = $parameter;
            }
        }

        $sortType = [
            'Homes_for_You',
            'Price_High_Low',
            'Price_Low_High',
            'Newest',
            'Bedrooms',
            'Bathrooms',
            'Square_Feet',
            'Lot_Size',
        ];
        $parameters['sort'] = array_rand( $sortType );

        return $parameters;
    }

    private function randomNumber( $numberSet, $maxNum = 100 )
    {
        $randNum = rand( 1, $maxNum );
        $prevNum = 0;
        foreach( $numberSet as $key => $value )
        {
            if( $randNum > $prevNum and $randNum <= $key )
            {
                return $value;
            }
        }
    }
    
    public function putMinPrice( $num = null )
    {
        if( $num )
        {
            $this->minPrice = $num;
        }
        elseif( !$this->hasCity )
        {
            $numberSet = [
                10  => null,
                20  => $this->state->avgHousePrice / 6,
                30  => $this->state->avgHousePrice / 5,
                40  => $this->state->avgHousePrice / 4,
                50  => $this->state->avgHousePrice / 3,
                60  => $this->state->avgHousePrice / 2, 
                70  => $this->state->avgHousePrice,
                80  => $this->state->avgHousePrice * 1.5,
                90  => $this->state->avgHousePrice * 2,
                100 => $this->state->avgHousePrice * 2.5
            ];
            $this->minPrice = $this->randomNumber( $numberSet );
        }
    }

    public function putMaxPrice( $num = null )
    {
        if( $num )
        {
            $this->maxPrice = $num;
        }
        elseif( !is_null( $this->minPrice ) )
        {
            $this->maxPrice =  $this->minPrice * 1.5 + $this->state->avgHousePrice / 4 + 50000;
        }
        elseif( $this->minPrice >= $this->state->avgHousePrice * 2.5 or $this->hasCity ) // If min value is maxed out..
        {
            // Leave max value null to get any value for max
        }
        /*
        else
        {
            $numberSet = [
                10  => $this->state->avgHousePrice / 2,
                20  => $this->state->avgHousePrice / 1.5,
                30  => $this->state->avgHousePrice / 1.25,
                40  => $this->state->avgHousePrice,
                50  => $this->state->avgHousePrice * 1.25,
                60  => $this->state->avgHousePrice * 1.5, 
                70  => $this->state->avgHousePrice * 2,
                80  => $this->state->avgHousePrice * 3,
                90  => $this->state->avgHousePrice * 5,
                100 => null
            ];
            $this->minPrice = $this->randomNumber( $numberSet );
        }
        */

        if( $this->minPrice >= $this->maxPrice and !is_null( $this->maxPrice ) )
        {
            $this->minPrice = null;
        }
    }
    
    public function putRentMinPrice( $num = null )
    {
        if( $num )
        {
            $this->rentMinPrice = $num;
        }
        elseif( !$this->hasCity )
        {
            $numberSet = [
                10  => null,
                20  => $this->state->avgRentPrice / 6,
                30  => $this->state->avgRentPrice / 5,
                40  => $this->state->avgRentPrice / 4,
                50  => $this->state->avgRentPrice / 3,
                60  => $this->state->avgRentPrice / 2, 
                70  => $this->state->avgRentPrice,
                80  => $this->state->avgRentPrice * 1.5,
                90  => $this->state->avgRentPrice * 2,
                100 => $this->state->avgRentPrice * 3
            ];
            $this->rentMinPrice = $this->randomNumber( $numberSet );
        }
    }

    public function putRentMaxPrice( $num = null )
    {
        if( $num )
        {
            $this->rentMaxPrice = $num;
        }
        elseif( $this->rentMinPrice >= $this->state->avgRentPrice * 3 or $this->hasCity ) // If min value is maxed out..
        {
            // Leave max value null to get any value for max
        }
        elseif( !is_null( $this->minPrice ) )
        {
            $this->rentMaxPrice =  $this->minPrice * 1.5 + $this->state->avgRentPrice / 4 + 300;
        }
        else
        {
            $numberSet = [
                10  => $this->state->avgRentPrice / 2,
                20  => $this->state->avgRentPrice / 1.5,
                30  => $this->state->avgRentPrice,
                40  => $this->state->avgRentPrice * 1.5,
                50  => $this->state->avgRentPrice * 2,
                60  => $this->state->avgRentPrice * 3, 
                70  => $this->state->avgRentPrice * 4,
                80  => $this->state->avgRentPrice * 5,
                90  => $this->state->avgRentPrice * 7,
                100 => null
            ];
            $this->rentMaxPrice = $this->randomNumber( $numberSet );
        }

        if( $this->rentMinPrice >= $this->rentMaxPrice and !is_null( $this->rentMaxPrice ) )
        {
            $this->rentMinPrice = null;
        }
    }
    
    public function putHomeType( $types = [] )
    {
        array_shift( $types );
        $this->homeType = implode( ', ', $types );
    }
    
    public function putBathsMinSale( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->bathsMin = $num;
        }
    }

    public function putBathsMinRent( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->bathsMin = $num;
        }
    }

    public function putBathsMaxSale( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->bathsMax = $num;
        }
    }

    public function putBathsMaxRent( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->bathsMax = $num;
        }
    }

    public function putBedsMinSale( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->bedsMin = $num;
        }
    }

    public function putBedsMinRent( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->bedsMin = $num;
        }
    }

    public function putBedsMaxSale( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->bedsMax = $num;
        }
    }
    
    public function putBedsMaxRent( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->bedsMax = $num;
        }
    }

    public function putLivingMinSale( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->livingMin = $num;
        }
    }

    public function putLivingMinRent( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->livingMin = $num;
        }
    }

    public function putLivingMaxSale( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->livingMax = $num;
        }
    }

    public function putLivingMaxRent( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->livingMax = $num;
        }
    }

    public function putLotMinSale( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->lotMin = $num;
        }
    }

    public function putLotMinRent( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->lotMin = $num;
        }
    }

    public function putLotMaxSale( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->lotMax = $num;
        }
    }

    public function putLotMaxRent( $num = null )
    {
        if( !empty( $num ) )
        {
            $this->lotMax = $num;
        }
    }
}
