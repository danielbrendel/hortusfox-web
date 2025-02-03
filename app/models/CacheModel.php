<?php

/**
 * Class CacheModel
 * 
 * Caching implementation model
 */
class CacheModel extends \Asatru\Database\Model {
	/**
	* Obtain value either from CacheModel or from closure
	*	
	*	@param string $ident The CacheModel item identifier
	*	@param int $timeInSeconds Amount of seconds the item shall be CacheModeld
	*	@param $closure Function to be called for the actual value
	*	@return mixed
	*/
	public static function remember($ident, $timeInSeconds, $closure)
	{
		$item = CacheModel::find($ident, 'ident');
		if ($item->count() == 0) {
			$value = $closure();
			
			$data = array(
				'ident' => $ident,
				'value' => $value,
				'updated_at' => date('Y-m-d H:i:s')
			);
			
			foreach ($data as $key => $val) {
				CacheModel::insert($key, $val);
			}
			
			CacheModel::go();
			
			return $value;
		} else {
			$data = $item->get(0);
			$dtLast = new DateTime(date('Y-m-d H:i:s', strtotime($data->get('updated_at'))));
			$dtLast->add(new DateInterval('PT' . $timeInSeconds . 'S'));
			$dtNow = new DateTime('now');

			if ($dtNow < $dtLast) {
				return $data->get('value');
			} else {
				$value = $closure();
				
				$updData = array(
					'value' => $value,
					'updated_at' => date('Y-m-d H:i:s')
				);
				
				foreach ($updData as $key => $val) {
					CacheModel::update($key, $val);
				}

				CacheModel::where('id', '=', $data->get('id'));
				
				CacheModel::go();
				
				return $value;
			}
		}
		
		return null;
	}
	
	/**
	 * Check for item existence
	 *
	 *	@param $ident
	 *  @return bool
	 */
	public static function has($ident)
	{
		$item = CacheModel::find($ident, 'ident');
		if ($item->count() > 0) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Get item and then delete it
	 *
	 *	@param $ident
	 *  @return mixed
	 */
	public static function pull($ident)
	{
		$item = CacheModel::find($ident, 'ident');
		if ($item->count() > 0) {
			$data = $item->get(0);
			
			CacheModel::where('id', '=', $item->get(0)->get('id'))->delete();
			
			return $data->get('value');
		}
		
		return null;
	}

	/**
	 * Reset cache of item
	 * 
	 * @param string $ident The item identifier
	 * @return bool
	 */
	public static function reset($ident)
	{
		$item = CacheModel::find($ident, 'ident');
		if ($item->count() > 0) {
			CacheModel::update('updated_at', '1970-01-05 00:00:00')->where('ident', '=', $ident)->go();

			return true;
		}
		
		return false;
	}
	
	/**
	 * Forget CacheModel item
	 * 
	 * @param string $ident The item identifier
	 * @return bool
	 */
	public static function forget($ident)
	{
		$item = CacheModel::find($ident, 'ident');
		if ($item->count() > 0) {
			CacheModel::where('id', '=', $item->get(0)->get('id'))->delete();
			
			return true;
		}
		
		return false;
	}

	/**
	 * Clear entire cache
	 * 
	 * @return void
	 */
	public static function clear()
	{
		static::raw('DELETE FROM `@THIS`');
	}
}
    