package com.brasibturger.controllers;

import com.brasibturger.dtos.ZoneDTO;
import com.brasibturger.services.ZoneService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/zones")
@RequiredArgsConstructor
public class ZoneController {
    
    private final ZoneService zoneService;
    
    @GetMapping
    public ResponseEntity<List<ZoneDTO>> getAllZones() {
        return ResponseEntity.ok(zoneService.getAllZones());
    }
    
    @GetMapping("/{id}")
    public ResponseEntity<ZoneDTO> getZoneById(@PathVariable Long id) {
        return ResponseEntity.ok(zoneService.getZoneById(id));
    }
    
    @GetMapping("/name/{nomZone}")
    public ResponseEntity<ZoneDTO> getZoneByName(@PathVariable String nomZone) {
        return ResponseEntity.ok(zoneService.getZoneByName(nomZone));
    }
    
    @PostMapping
    public ResponseEntity<ZoneDTO> createZone(@Valid @RequestBody ZoneDTO zoneDTO) {
        return ResponseEntity.status(HttpStatus.CREATED).body(zoneService.createZone(zoneDTO));
    }
    
    @PutMapping("/{id}")
    public ResponseEntity<ZoneDTO> updateZone(
            @PathVariable Long id,
            @Valid @RequestBody ZoneDTO zoneDTO) {
        return ResponseEntity.ok(zoneService.updateZone(id, zoneDTO));
    }
    
    @DeleteMapping("/{id}")
    public ResponseEntity<Void> deleteZone(@PathVariable Long id) {
        zoneService.deleteZone(id);
        return ResponseEntity.noContent().build();
    }
}
