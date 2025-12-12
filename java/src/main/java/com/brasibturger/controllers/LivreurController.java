package com.brasibturger.controllers;

import com.brasibturger.dtos.LivreurDTO;
import com.brasibturger.services.LivreurService;
import jakarta.validation.Valid;
import lombok.RequiredArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/livreurs")
@RequiredArgsConstructor
public class LivreurController {
    
    private final LivreurService livreurService;
    
    @GetMapping
    public ResponseEntity<List<LivreurDTO>> getAllLivreurs() {
        return ResponseEntity.ok(livreurService.getAllLivreurs());
    }
    
    @GetMapping("/actifs")
    public ResponseEntity<List<LivreurDTO>> getActiveLivreurs() {
        return ResponseEntity.ok(livreurService.getActiveLivreurs());
    }
    
    @GetMapping("/{id}")
    public ResponseEntity<LivreurDTO> getLivreurById(@PathVariable Long id) {
        return ResponseEntity.ok(livreurService.getLivreurById(id));
    }
    
    @GetMapping("/zone/{zoneId}")
    public ResponseEntity<List<LivreurDTO>> getLivreursByZone(@PathVariable Long zoneId) {
        return ResponseEntity.ok(livreurService.getLivreursByZone(zoneId));
    }
    
    @PostMapping
    public ResponseEntity<LivreurDTO> createLivreur(@Valid @RequestBody LivreurDTO livreurDTO) {
        return ResponseEntity.status(HttpStatus.CREATED).body(livreurService.createLivreur(livreurDTO));
    }
    
    @PutMapping("/{id}")
    public ResponseEntity<LivreurDTO> updateLivreur(
            @PathVariable Long id,
            @Valid @RequestBody LivreurDTO livreurDTO) {
        return ResponseEntity.ok(livreurService.updateLivreur(id, livreurDTO));
    }
    
    @DeleteMapping("/{id}")
    public ResponseEntity<Void> deleteLivreur(@PathVariable Long id) {
        livreurService.deleteLivreur(id);
        return ResponseEntity.noContent().build();
    }
}
