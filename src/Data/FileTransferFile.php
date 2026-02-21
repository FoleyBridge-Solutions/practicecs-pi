<?php

declare(strict_types=1);

namespace FoleyBridgeSolutions\PracticeCsPI\Data;

/**
 * File transfer file data object returned from the PracticeCS API.
 *
 * @property-read int $fileTransferFileKey
 * @property-read int $fileTransferKey
 * @property-read int $fileTransferFileStatusKey
 * @property-read int $fileTransferFileSignatureStatusKey
 * @property-read string $fileName
 * @property-read int $fileSize
 * @property-read string $sourcePath
 * @property-read string $sourceMachineName
 * @property-read string|null $signatureRequestDateUtc
 * @property-read string|null $signatureSignDateUtc
 * @property-read string|null $signatureRejectDateUtc
 */
class FileTransferFile
{
    public function __construct(
        public readonly int $fileTransferFileKey,
        public readonly int $fileTransferKey = 0,
        public readonly int $fileTransferFileStatusKey = 0,
        public readonly int $fileTransferFileSignatureStatusKey = 0,
        public readonly string $fileName = '',
        public readonly int $fileSize = 0,
        public readonly string $sourcePath = '',
        public readonly string $sourceMachineName = '',
        public readonly ?string $signatureRequestDateUtc = null,
        public readonly ?string $signatureSignDateUtc = null,
        public readonly ?string $signatureRejectDateUtc = null,
    ) {}

    /**
     * Create a FileTransferFile from an API response array.
     *
     * @param  array  $data  API response data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            fileTransferFileKey: (int) $data['file_transfer_file_KEY'],
            fileTransferKey: (int) ($data['file_transfer_KEY'] ?? 0),
            fileTransferFileStatusKey: (int) ($data['file_transfer_file_status_KEY'] ?? 0),
            fileTransferFileSignatureStatusKey: (int) ($data['file_transfer_file_signature_status_KEY'] ?? 0),
            fileName: (string) ($data['file_name'] ?? ''),
            fileSize: (int) ($data['file_size'] ?? 0),
            sourcePath: (string) ($data['source_path'] ?? ''),
            sourceMachineName: (string) ($data['source_machine_name'] ?? ''),
            signatureRequestDateUtc: $data['signature_request_date_utc'] ?? null,
            signatureSignDateUtc: $data['signature_sign_date_utc'] ?? null,
            signatureRejectDateUtc: $data['signature_reject_date_utc'] ?? null,
        );
    }

    /**
     * Convert to array representation.
     */
    public function toArray(): array
    {
        return [
            'file_transfer_file_KEY' => $this->fileTransferFileKey,
            'file_transfer_KEY' => $this->fileTransferKey,
            'file_transfer_file_status_KEY' => $this->fileTransferFileStatusKey,
            'file_transfer_file_signature_status_KEY' => $this->fileTransferFileSignatureStatusKey,
            'file_name' => $this->fileName,
            'file_size' => $this->fileSize,
            'source_path' => $this->sourcePath,
            'source_machine_name' => $this->sourceMachineName,
            'signature_request_date_utc' => $this->signatureRequestDateUtc,
            'signature_sign_date_utc' => $this->signatureSignDateUtc,
            'signature_reject_date_utc' => $this->signatureRejectDateUtc,
        ];
    }
}
