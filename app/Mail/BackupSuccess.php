<?php

namespace App\Mail;

use App\Models\Backup;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Facades\Storage;

class BackupSuccess extends Mailable
{
    use Queueable;

    public Backup $backup;

    public string $sizeFormatted;

    public function __construct(Backup $backup)
    {
        $this->backup = $backup;
        $bytes = max($backup->size ?? 0, 0);
        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $this->sizeFormatted = $bytes ? round($bytes / pow(1024, $pow), 2).' '.$units[$pow] : '-';
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Backup Database Berhasil - '.$this->backup->created_at->format('d M Y H:i'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.backup-success',
        );
    }

    public function attachments(): array
    {
        if (! Storage::disk('backups')->exists($this->backup->path)) {
            return [];
        }

        return [
            Attachment::fromPath(Storage::disk('backups')->path($this->backup->path))
                ->as($this->backup->filename)
                ->withMime('application/zip'),
        ];
    }
}
