<?php

/**
 * Application common helper functions
 */

use App\Services\ZebraImageService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

/**
 * For accessing the application private files.
 *
 * @param mixed $key
 * 
 */
if (! function_exists('file_asset'))
{
	function file_asset($type, $file = '', $size = 'default')
	{
		if(config('filesystems.default') == 's3') {
			$type = str_replace('-', '.', $type);
			$basePath = config('params.' . $type);
			$path = null;
			if($size == 'default') {
				$path = config('filesystems.disks.s3.url') . $basePath . '/' . $file;
			}
			else {
				// if(file_exists_in_s3('zebra/' .$basePath . '/' . $size . '/' . $file)) {
				// 	$path = config('filesystems.disks.s3.url') . 'zebra/' . $basePath . '/' . $size . '/' . $file;
				// }
				// else if(file_exists_in_s3('zebra/'. $basePath . '/' . 'thumbnail-new' . '/' . $file)) {
				// 	$path = config('filesystems.disks.s3.url') . 'zebra/' . $basePath . '/' . 'thumbnail-new' . '/' . $file;
				// }
				// else {
				// 	$path = config('filesystems.disks.s3.url') . $basePath . '/' . $file;
				// }
				// $path = config('filesystems.disks.s3.url') . $basePath . '/' . $file;

				$path = config('filesystems.disks.s3.url') . 'zebra/' . $basePath . '/' . $size . '/' . $file;
			}
			
			return $path;
		}
		
		return route('file.index', ['type' => $type, 'size' => $size, 'name' => $file]);
	}
}

/**
 * For accessing the application private files.
 *
 * @param mixed $key
 * 
 */
if (! function_exists('file_asset_pdf'))
{
	function file_asset_pdf($type, $file = '', $originalName = '')
	{
		if(config('filesystems.default') == 's3') {
			$type = str_replace('-', '.', $type);
			$basePath = config('params.' . $type);
			$path = config('filesystems.disks.s3.url') . $basePath . '/' . $file;
			return $path;
		}

		return route('file.create', ['type' => $type, 'name' => $file, 'original' => $originalName]);
	}
}

/**
 * Date formater
 *
 * @param string $date
 * @param string $format
 */
if (! function_exists('dateFormat'))
{
	function dateFormat($date, $format = 'd M Y')
	{
		return date($format, strtotime($date));
	}
}

/**
 * Date time formater
 *
 * @param string $date
 * @param string $format
 */
if (! function_exists('dateTimeFormat'))
{
	function dateTimeFormat($date, $format = 'd M Y h:i A')
	{
		return date($format, strtotime($date));
	}
}

/**
 * Date time formater
 *
 * @param string $date
 * @param string $format
 */
if (! function_exists('timeFormat'))
{
	function timeFormat($date, $format = 'h:i A')
	{
		return date($format, strtotime($date));
	}
}

/**
 * Generate otp
 *
 * @param string $date
 * @param string $format
 */
if (! function_exists('generate_otp'))
{
	function generate_otp()
	{
		if (App::environment('production')) {
		 	return rand(0001, 9999);
		}

		return 1234;
	}
}

/**
 * Compress & resize image
 */
if (! function_exists('compressAndResizeImage'))
{
	function compressAndResizeImage($sourceUrl, $destinationUrl, $size = 'FHD') {

		$info = getimagesize($sourceUrl);
		$imageWidth = $info[0];
		$imageHeight = $info[1];

		$isResized = false;

		list($width, $height) = config('image-resize.sizes.' . $size);

		if( ($imageWidth >= $width) || ($imageHeight >= $height) )
		{
			
			$image = new ZebraImageService();
			$image->source_path = $sourceUrl;
			$image->target_path = $destinationUrl;
			
			$image->resize($width, $height, ZEBRA_IMAGE_NOT_BOXED, -1);
			$isResized = true;
		}
		
		if(!$isResized)
		{
			$fileSizeInMB = filesize($sourceUrl)/(1000*1000);
			
			if($fileSizeInMB > 1)
				return compressImage($sourceUrl, $destinationUrl, 90);
		}
		
		
		return $destinationUrl;
	}
}

/**
 * Compress image
 */
if (! function_exists('compressImage'))
{
	function compressImage($sourceUrl, $destinationUrl, $quality = 50) {
		
		$info = getimagesize($sourceUrl);
	
		switch ($info['mime']) {
			case 'image/jpeg':
				$image = imagecreatefromjpeg($sourceUrl);
				break;
			
			case 'image/gif':
				$image = imagecreatefromgif($sourceUrl);
				break;
			
			case 'image/png':
				$image = imagecreatefrompng($sourceUrl);
				break;
			
			default:
				$image = false;
				break;
		}
	
		if(!$image){
			return false;
		}
		//save file
		imagejpeg($image, $destinationUrl, $quality);
	
		//return destination file
		return $destinationUrl;
	}
}

/**
 * resize image
 */
if (! function_exists('resizeImage'))
{
	function resizeImage($sourceUrl, $destinationUrl, $size = 'thumbnail') {

		$info = getimagesize($sourceUrl);
		$imageWidth = $info[0];
		$imageHeight = $info[1];

		$isResized = false;

		list($width, $height) = config('image-resize.sizes.' . $size);

		if( ($imageWidth >= $width) || ($imageHeight >= $height) )
		{
			
			$image = new ZebraImageService();
			$image->source_path = $sourceUrl;
			$image->target_path = $destinationUrl;
			
			$image->resize($width, $height, ZEBRA_IMAGE_CROP_CENTER, -1);
			$isResized = true;
		    Log::info("resised" . $isResized);
		}
		
		if(!$isResized)
		{
			$fileSizeInMB = filesize($sourceUrl)/(1000*1000);
			
			if($fileSizeInMB > 1)
				return compressImage($sourceUrl, $destinationUrl, 90);
		}
		
		
		return $destinationUrl;
	}
}

/**
 * time ago
 */
if (! function_exists('get_time_ago'))
{
	function get_time_ago($created_at) {
        $start_datetime = new DateTime(now()); 
        $diff = $start_datetime->diff(new DateTime($created_at)); 
        if($diff->y >= 1) {
            return $diff->y . ' year ago'; 
        }
        
        if($diff->m >= 1) {
            return $diff->m . ' month ago';
        }

        if($diff->d >= 21) {
            return '3 weeks ago';
        }

        if($diff->d >= 14) {
            return '2 weeks ago';
        }

        if($diff->d >= 7) {
            return '1 week ago';
        }

        if($diff->d > 1) {
            return $diff->d . ' days ago';
        }

        if($diff->d == 1) {
            return 'yesterday';
        }

        if($diff->h < 24){
            if($diff->h >= 1 ) {
                return $diff->h . ' hrs ago';
            }
            if($diff->i < 60 && $diff->i > 0) {
                return $diff->i . ' min ago';
            }
            return 'now';
        }
	}
}






